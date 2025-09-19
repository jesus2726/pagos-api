<?php

namespace App\Services;

use App\Helpers\GeneralHelper;
use App\Repositories\ClientRepository;

class ClientService
{
    protected $clientRepository;

    public function __construct(ClientRepository $clientRepository)
    {
        $this->clientRepository = $clientRepository;
    }

    public function getPaginated($perPage = 15, $filters = [])
    {
        return $this->clientRepository->paginated($perPage, $filters);
    }

    public function create(array $data)
    {
        $data['uuid'] = GeneralHelper::generateUuid();
        return $this->clientRepository->create($data);
    }

    public function update(array $data, $uuid)
    {
        $client = $this->clientRepository->findByUuid($uuid);
        if (!$client) {
            throw new \Exception('Client not found');
        }
        return $this->clientRepository->update($client, $data);
    }

    public function delete($uuid)
    {
        $client = $this->clientRepository->findByUuid($uuid);
        if (!$client) {
            throw new \Exception('Client not found');
        }
        return $this->clientRepository->delete($client->id);
    }

    public function findByUuid($uuid)
    {
        $client = $this->clientRepository->findByUuid($uuid);
        if (!$client) {
            throw new \Exception('Client not found');
        }
        return $client->load(['beneficiaries', 'paymentOrders']);
    }
}
