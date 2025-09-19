<?php

namespace App\Repositories;

use App\Models\Beneficiary;
use App\Models\Client;

class BeneficiaryRepository
{
    public function paginatedByClient($uuid, $perPage = 15, $filters = [])
    {
        $client = Client::where('uuid', $uuid)->first();
        $query = Beneficiary::where('client_id', $client->id);

        if (isset($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('account_number', 'like', "%{$search}%")
                    ->orWhere('bank_name', 'like', "%{$search}%");
            });
        }

        return $query->orderBy('created_at', 'desc')->paginate($perPage);
    }

    public function create(array $data)
    {
        $data['client_id'] = Client::where('uuid', $data['client_uuid'])->first()->id;
        return Beneficiary::create($data);
    }

    public function update(Beneficiary $beneficiary, array $data)
    {
        $beneficiary->update($data);
        return $beneficiary->fresh();
    }

    public function delete($id)
    {
        return Beneficiary::find($id)->delete();
    }

    public function findByUuid($uuid)
    {
        return Beneficiary::where('uuid', $uuid)->first();
    }

}
