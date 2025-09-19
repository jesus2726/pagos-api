<?php

namespace App\Repositories;

use App\Models\TransactionAudit;
use App\Models\Client;
use App\Models\PaymentOrder;

class TransactionAuditRepository
{
    public function create(array $data)
    {
        return TransactionAudit::create($data);
    }

    public function findByUuid($uuid)
    {
        return TransactionAudit::where('uuid', $uuid)->first();
    }

    public function getClientAuditHistory(string $clientUuid, int $limit = 50)
    {
        $client = Client::where('uuid', $clientUuid)->first();
        
        if (!$client) {
            return collect();
        }

        return TransactionAudit::where('client_id', $client->id)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    public function getTransactionAudit(int $relatedId, string $transactionType)
    {

        return TransactionAudit::where('related_id', $relatedId)
            ->where('transaction_type', $transactionType)
            ->first();
    }
}
