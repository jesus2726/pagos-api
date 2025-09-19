<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreClientRequest;
use App\Http\Requests\UpdateClientRequest;
use App\Http\Resources\ClientResource;
use App\Services\ClientService;
use App\Helpers\GeneralHelper;
use Illuminate\Http\Request;

/**
 * @tags Client
 */
class ClientController extends Controller
{
    protected $service;

    public function __construct(ClientService $service)
    {
        $this->service = $service;
    }

    /**
     * List clients
     *
     * Retrieve a paginated list of all clients with optional filters.
     * Supports search by name or email, status filtering, and balance range filtering.
     */
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 15);
        $filters = $request->only(['search', 'status', 'min_balance', 'max_balance']);

        $clients = $this->service->getPaginated($perPage, $filters);

        return GeneralHelper::apiResponse([
            'data' => ClientResource::collection($clients->items()),
            'pagination' => [
                'current_page' => $clients->currentPage(),
                'last_page' => $clients->lastPage(),
                'per_page' => $clients->perPage(),
                'total' => $clients->total(),
                'from' => $clients->firstItem(),
                'to' => $clients->lastItem(),
            ]
        ]);
    }

    /**
     * Create new client
     *
     * Create a new client in the system with an initial balance.
     * Validates email uniqueness and required fields.
     */
    public function store(StoreClientRequest $request)
    {
        try {
            $client = $this->service->create($request->validated());
            return GeneralHelper::apiResponse(new ClientResource($client), 'Client created successfully', 201);
        } catch (\Exception $e) {
            return GeneralHelper::apiError($e->getMessage(), 422);
        }
    }

    /**
     * Get client details
     *
     * Retrieve detailed information for a specific client by UUID.
     */
    public function show($uuid)
    {
        try {
            $client = $this->service->findByUuid($uuid);
            return GeneralHelper::apiResponse(new ClientResource($client));
        } catch (\Exception $e) {
            return GeneralHelper::apiError($e->getMessage(), 404);
        }
    }

    /**
     * Update client
     *
     * Update existing client information including name, email, and balance.
     * Validates email uniqueness and required fields.
     */
    public function update(UpdateClientRequest $request, $uuid)
    {
        try {
            $client = $this->service->update($request->validated(), $uuid);
            return GeneralHelper::apiResponse(new ClientResource($client), 'Client updated successfully');
        } catch (\Exception $e) {
            return GeneralHelper::apiError($e->getMessage(), 404);
        }
    }

    /**
     * Delete client
     *
     * Remove a client from the system. This action cannot be undone.
     * All associated beneficiaries and payment orders will also be affected.
     */
    public function destroy($uuid)
    {
        try {
            $this->service->delete($uuid);
            return GeneralHelper::apiResponse(null, 'Client deleted successfully');
        } catch (\Exception $e) {
            return GeneralHelper::apiError($e->getMessage(), 422);
        }
    }
}
