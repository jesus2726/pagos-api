<?php

namespace App\Repositories;

use App\Models\Client;

class ClientRepository
{
    public function all()
    {
        return Client::all();
    }

    public function paginated($perPage = 15, $filters = [])
    {
        $query = Client::query();

        if (isset($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (isset($filters['min_balance'])) {
            $query->where('balance', '>=', $filters['min_balance']);
        }

        if (isset($filters['max_balance'])) {
            $query->where('balance', '<=', $filters['max_balance']);
        }

        return $query->orderBy('created_at', 'desc')->paginate($perPage);
    }

    public function create(array $data)
    {
        return Client::create($data);
    }

    public function update(Client $client, array $data)
    {
        $client->update($data);
        return $client->fresh();
    }

    public function delete($id)
    {
        return Client::find($id)->delete();
    }

    public function findByUuid($uuid)
    {
        return Client::where('uuid', $uuid)->first();
    }

    public function updateBalance(Client $client, $newBalance)
    {
        $client->update(['balance' => $newBalance]);
        return $client->fresh();
    }
}
