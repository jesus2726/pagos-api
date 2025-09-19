<?php

namespace App\Services;

use App\Helpers\GeneralHelper;
use App\Repositories\BeneficiaryRepository;

class BeneficiaryService
{
    protected $beneficiaryRepository;

    public function __construct(BeneficiaryRepository $beneficiaryRepository)
    {
        $this->beneficiaryRepository = $beneficiaryRepository;
    }

    public function getPaginatedByClient($uuid, $perPage = 15, $filters = [])
    {
        return $this->beneficiaryRepository->paginatedByClient($uuid, $perPage, $filters);
    }

    public function create(array $data)
    {
        $data['uuid'] = GeneralHelper::generateUuid();
        return $this->beneficiaryRepository->create($data);
    }

    public function find($uuid)
    {
        $beneficiary = $this->beneficiaryRepository->findByUuid($uuid);
        if (!$beneficiary) {
            throw new \Exception('Beneficiary not found');
        }
        return $beneficiary;
    }

    public function update($uuid, array $data)
    {
        $beneficiary = $this->beneficiaryRepository->findByUuid($uuid);
        if (!$beneficiary) {
            throw new \Exception('Beneficiary not found');
        }
        return $this->beneficiaryRepository->update($beneficiary, $data);
    }

    public function delete($uuid)
    {
        $beneficiary = $this->beneficiaryRepository->findByUuid($uuid);
        if (!$beneficiary) {
            throw new \Exception('Beneficiary not found');
        }
        return $this->beneficiaryRepository->delete($beneficiary->id);
    }


}
