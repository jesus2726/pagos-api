<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBeneficiaryRequest;
use App\Http\Requests\UpdateBeneficiaryRequest;
use App\Http\Resources\BeneficiaryResource;
use App\Services\BeneficiaryService;
use App\Helpers\GeneralHelper;
use Illuminate\Http\Request;

/**
 * @tags Beneficiary
 */
class BeneficiaryController extends Controller
{
    protected $service;

    public function __construct(BeneficiaryService $service)
    {
        $this->service = $service;
    }

    /**
     * List beneficiaries by client
     *
     * Retrieve a paginated list of beneficiaries associated with a specific client.
     * Supports search by name, email, account number, or bank name.
     *
     * @param int $clientUuid The UUID of the client to get beneficiaries for
     */
    public function index(Request $request, $clientUuid)
    {
        $perPage = $request->get('per_page', 15);
        $filters = $request->only(['search']);

        $beneficiaries = $this->service->getPaginatedByClient($clientUuid, $perPage, $filters);

        return GeneralHelper::apiResponse([
            'data' => BeneficiaryResource::collection($beneficiaries->items()),
            'pagination' => [
                'current_page' => $beneficiaries->currentPage(),
                'last_page' => $beneficiaries->lastPage(),
                'per_page' => $beneficiaries->perPage(),
                'total' => $beneficiaries->total(),
                'from' => $beneficiaries->firstItem(),
                'to' => $beneficiaries->lastItem(),
            ]
        ]);
    }

    /**
     * Create new beneficiary
     *
     * Add a new beneficiary associated with a specific client.
     * Validates bank account details and required information.
     */
    public function store(StoreBeneficiaryRequest $request)
    {
        try {
            $beneficiary = $this->service->create($request->validated());
            return GeneralHelper::apiResponse(new BeneficiaryResource($beneficiary), 'Beneficiary created successfully', 201);
        } catch (\Exception $e) {
            return GeneralHelper::apiError($e->getMessage(), 422);
        }
    }

    /**
     * Get beneficiary details
     *
     * Retrieve detailed information for a specific beneficiary by UUID.
     */
    public function show($uuid)
    {
        try {
            $beneficiary = $this->service->find($uuid);
            return GeneralHelper::apiResponse(new BeneficiaryResource($beneficiary));
        } catch (\Exception $e) {
            return GeneralHelper::apiError($e->getMessage(), 404);
        }
    }

    /**
     * Update beneficiary
     *
     * Update existing beneficiary information including bank details and contact information.
     */
    public function update(UpdateBeneficiaryRequest $request, $uuid)
    {
        try {
            $beneficiary = $this->service->update($uuid, $request->validated());
            return GeneralHelper::apiResponse(new BeneficiaryResource($beneficiary), 'Beneficiary updated successfully');
        } catch (\Exception $e) {
            return GeneralHelper::apiError($e->getMessage(), 422);
        }
    }

    /**
     * Delete beneficiary
     *
     * Remove a beneficiary from the system. This action cannot be undone.
     * Associated payment orders will be affected.
     */
    public function destroy($uuid)
    {
        try {
            $this->service->delete($uuid);
            return GeneralHelper::apiResponse(null, 'Beneficiary deleted successfully');
        } catch (\Exception $e) {
            return GeneralHelper::apiError($e->getMessage(), 422);
        }
    }
}
