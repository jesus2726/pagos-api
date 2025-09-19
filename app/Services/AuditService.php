<?php

namespace App\Services;

use App\Models\TransactionAudit;
use App\Helpers\GeneralHelper;
use App\Repositories\TransactionAuditRepository;
use Illuminate\Support\Facades\Log;

class AuditService
{
    protected TransactionAuditRepository $auditRepository;

    public function __construct(TransactionAuditRepository $auditRepository)
    {
        $this->auditRepository = $auditRepository;
    }

    public function logTransaction(
        string $transactionType,
        int $clientId,
        ?int $relatedId = null,
        ?string $relatedType = null,
        ?float $amount = null,
        ?float $balanceBefore = null,
        ?float $balanceAfter = null,
        string $status = 'successful',
        ?string $description = null,
        ?array $metadata = null
    ): TransactionAudit {
        try {
            $audit = $this->auditRepository->create([
                'uuid' => GeneralHelper::generateUuid(),
                'transaction_type' => $transactionType,
                'client_id' => $clientId,
                'related_id' => $relatedId,
                'related_type' => $relatedType,
                'amount' => $amount,
                'balance_before' => $balanceBefore,
                'balance_after' => $balanceAfter,
                'status' => $status,
                'description' => $description,
                'metadata' => $metadata,
            ]);

            Log::info('Transaction audited', [
                'audit_id' => $audit->id,
                'transaction_type' => $transactionType,
                'client_id' => $clientId,
                'amount' => $amount,
                'status' => $status
            ]);

            return $audit;

        } catch (\Exception $e) {
            Log::error('Error creating transaction audit', [
                'transaction_type' => $transactionType,
                'client_id' => $clientId,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    public function getClientAuditHistory(string $clientUuid, int $limit = 50)
    {
        return $this->auditRepository->getClientAuditHistory($clientUuid, $limit);
    }

    public function getTransactionAudit(int $relatedId, string $transactionType)
    {
        return $this->auditRepository->getTransactionAudit($relatedId, $transactionType);
    }
}
