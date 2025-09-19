<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePaymentOrderRequest;
use App\Http\Resources\PaymentOrderResource;
use App\Services\PaymentOrderService;
use App\Exceptions\InsufficientBalanceException;
use App\Exceptions\PaymentOrderException;
use App\Helpers\GeneralHelper;
use Illuminate\Http\Request;

/**
 * @tags PaymentOrder
 */
class PaymentOrderController extends Controller
{
    protected $paymentOrderService;

    public function __construct(PaymentOrderService $service)
    {
        $this->paymentOrderService = $service;
    }

    /**
     * List payment orders
     *
     * Retrieve a paginated list of all payment orders with optional filters.
     * Supports filtering by client, status, amount range, and date range.
     */
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 15);
        $filters = $request->only(['client_uuid', 'status', 'min_amount', 'max_amount', 'date_from', 'date_to']);

        $orders = $this->paymentOrderService->getPaginated($perPage, $filters);

        return GeneralHelper::apiResponse([
            'data' => PaymentOrderResource::collection($orders->items()),
            'pagination' => [
                'current_page' => $orders->currentPage(),
                'last_page' => $orders->lastPage(),
                'per_page' => $orders->perPage(),
                'total' => $orders->total(),
                'from' => $orders->firstItem(),
                'to' => $orders->lastItem(),
            ]
        ]);
    }

    /**
     * Create payment order
     *
     * Create a new payment order with multiple individual payments.
     * Automatically validates that the client has sufficient balance.
     * All payments are processed atomically - either all succeed or all fail.
     */
    public function store(StorePaymentOrderRequest $request)
    {
        try {
            $order = $this->paymentOrderService->createOrder(
                $request->validated()['client_uuid'],
                $request->validated()['payments']
            );

            return GeneralHelper::apiResponse(new PaymentOrderResource($order), 'Payment order created successfully', 201);
        } catch (InsufficientBalanceException $e) {
            return GeneralHelper::apiResponse(null, $e->getMessage(), 422);
        } catch (PaymentOrderException $e) {
            return GeneralHelper::apiResponse(null, $e->getMessage(), 422);
        } catch (\Exception $e) {
            return GeneralHelper::apiResponse(null, 'Internal server error', 500);
        }
    }

    /**
     * Get payment order details
     *
     * Retrieve detailed information for a specific payment order by UUID.
     * Includes all associated payments and beneficiary information.
     */
    public function show($uuid)
    {
        try {
            $order = $this->paymentOrderService->findByUuid($uuid);
            return GeneralHelper::apiResponse(new PaymentOrderResource($order));
        } catch (\Exception $e) {
            return GeneralHelper::apiError('Payment order not found', 404);
        }
    }
}
