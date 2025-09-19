<?php

namespace App\Repositories;

use App\Models\Client;
use App\Models\PaymentOrder;

class PaymentOrderRepository
{
    public function find($id)
    {
        return PaymentOrder::findOrFail($id);
    }

    public function create(array $data)
    {
        return PaymentOrder::create($data);
    }

    public function update(PaymentOrder $order, array $data)
    {
        $order->update($data);
        return $order->fresh();
    }

    public function all()
    {
        return PaymentOrder::with(['client', 'payments.beneficiary'])->get();
    }

    public function paginated($perPage = 15, $filters = [])
    {
        $query = PaymentOrder::with(['client', 'payments.beneficiary']);

        if (isset($filters['client_uuid'])) {
            $client = Client::where('uuid', $filters['client_uuid'])->first();
            $query->where('client_id', $client->id);
        }

        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (isset($filters['min_amount'])) {
            $query->where('total_amount', '>=', $filters['min_amount']);
        }

        if (isset($filters['max_amount'])) {
            $query->where('total_amount', '<=', $filters['max_amount']);
        }

        if (isset($filters['date_from'])) {
            $query->whereDate('created_at', '>=', $filters['date_from']);
        }

        if (isset($filters['date_to'])) {
            $query->whereDate('created_at', '<=', $filters['date_to']);
        }

        return $query->orderBy('created_at', 'desc')->paginate($perPage);
    }

    public function findByUuid($uuid)
    {
        return PaymentOrder::where('uuid', $uuid)->firstOrFail();
    }

    public function findByClient($clientId)
    {
        return PaymentOrder::where('client_id', $clientId)
            ->with(['payments.beneficiary'])
            ->get();
    }
}
