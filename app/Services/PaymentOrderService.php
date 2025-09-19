<?php

namespace App\Services;

use App\Helpers\GeneralHelper;
use App\Models\PaymentOrder;
use App\Repositories\ClientRepository;
use App\Repositories\BeneficiaryRepository;
use App\Repositories\PaymentOrderRepository;
use App\Repositories\PaymentRepository;
use App\Services\AuditService;
use App\Exceptions\InsufficientBalanceException;
use App\Exceptions\PaymentOrderException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PaymentOrderService
{
    protected $beneficiaryRepository;
    protected $clientRepository;
    protected $orderRepository;
    protected $paymentRepository;
    protected $auditService;

    public function __construct(
        BeneficiaryRepository $beneficiaryRepository,
        ClientRepository $clientRepository,
        PaymentOrderRepository $orderRepository,
        PaymentRepository $paymentRepository,
        AuditService $auditService
    ) {
        $this->beneficiaryRepository = $beneficiaryRepository;
        $this->clientRepository = $clientRepository;
        $this->orderRepository = $orderRepository;
        $this->paymentRepository = $paymentRepository;
        $this->auditService = $auditService;
    }

    public function createOrder($clientUuid, array $payments)
    {
        return DB::transaction(function () use ($clientUuid, $payments) {
            try {
                $client = $this->clientRepository->findByUuid($clientUuid);
                $totalAmount = collect($payments)->sum('amount');
                $this->checkBalance($client, $totalAmount);

                $order = $this->create($client->id, $totalAmount);

                $this->createPayments($order->id, $payments);

                $this->update($order, 'successful');

                // Descontar saldo del cliente
                $newBalance = $client->balance - $totalAmount;

                $this->createAudit(
                    $order,
                    $totalAmount,
                    $client,
                    $newBalance,
                    $payments
                );

                $this->logSuccess($order, $totalAmount, $clientUuid, $payments);

                $this->clientRepository->updateBalance($client, $newBalance);

                return $order->load(['payments.beneficiary', 'client']);
            } catch (InsufficientBalanceException $e) {
                throw $e;
            } catch (\Exception $e) {
                $this->logError($order, $totalAmount, $clientUuid, $payments);
                throw new PaymentOrderException("Error processing payment order: " . $e->getMessage());
            }
        });
    }

    public function getPaginated($perPage = 15, $filters = [])
    {
        return $this->orderRepository->paginated($perPage, $filters);
    }

    public function findByUuid($uuid)
    {
        $order = $this->orderRepository->findByUuid($uuid);
        return $order->load(['payments.beneficiary', 'client']);
    }

    private function checkBalance($client, $totalAmount)
    {
        // Verificar saldo suficiente
        if ($client->balance < $totalAmount) {
            Log::warning('Saldo insuficiente para cliente', [
                'client_id' => $client->id,
                'current_balance' => $client->balance,
                'required_amount' => $totalAmount
            ]);
            throw new InsufficientBalanceException(
                "Saldo insuficiente. Saldo actual: {$client->balance}, Monto requerido: {$totalAmount}"
            );
        }
    }

    private function create($clientId, $totalAmount)
    {
        return $this->orderRepository->create([
            'client_id'    => $clientId,
            'total_amount' => $totalAmount,
            'status'       => 'pending',
            'uuid'         => GeneralHelper::generateUuid(),
        ]);
    }

    private function createAudit($order, $totalAmount, $client, $newBalance, $payments)
    {
        // Crear auditoría de la transacción
        $this->auditService->logTransaction(
            'payment_order',
            $client->id,
            $order->id,
            PaymentOrder::class,
            $totalAmount,
            $client->balance,
            $newBalance,
            'successful',
            "Orden de pago procesada exitosamente con {$totalAmount} pagos",
            [
                'payments_count' => count($payments),
                'beneficiaries' => collect($payments)->pluck('beneficiary_id')->toArray()
            ]
        );
    }

    private function createPayments($orderId, $payments)
    {
        foreach ($payments as $payment) {
            $beneficiary = $this->beneficiaryRepository->findByUuid($payment['beneficiary_uuid']);
            $this->paymentRepository->create([
                'payment_order_id' => $orderId,
                'beneficiary_id'   => $beneficiary->id,
                'amount'           => $payment['amount'],
                'status'           => 'successful',
                'uuid'             => GeneralHelper::generateUuid(),
            ]);
        }
    }

    private function logError($order, $totalAmount, $clientId, $payments)
    {
        Log::error('Error al crear orden de pago', [
            'order_id' => $order->id,
            'client_id' => $clientId,
            'total_amount' => $totalAmount,
            'payments_count' => count($payments)
        ]);
    }

    private function logSuccess($order, $totalAmount, $clientUuid, $payments)
    {
        Log::info('Orden de pago creada exitosamente', [
            'order_id' => $order->id,
            'client_id' => $clientUuid,
            'total_amount' => $totalAmount,
            'payments_count' => count($payments)
        ]);
    }

    private function update($order, $status)
    {
        return $this->orderRepository->update($order, [
            'status' => $status,
            'processed_at' => now()
        ]);
    }
}
