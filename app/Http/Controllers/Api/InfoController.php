<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Helpers\GeneralHelper;

/**
 * @tags Info
 */
class InfoController extends Controller
{
    /**
     * Get API information
     *
     * Retrieve general information about the Payment API including
     * available endpoints, features, and system capabilities.
     */
    public function info()
    {
        $info = [
            'name' => 'Payment API',
            'version' => '1.0.0',
            'description' => 'REST API for managing payment disbursements to various platforms',
            'features' => [
                'Client Management' => 'Register and manage clients with initial balance',
                'Beneficiary Management' => 'Associate beneficiaries with clients for payments',
                'Payment Orders' => 'Create orders with multiple payments and balance validation',
                'Audit & History' => 'Complete transaction tracking and audit trail',
                'Pagination & Filters' => 'Optimized listings with advanced filtering',
            ],
            'endpoints' => [
                'clients' => [
                    'GET /api/clients' => 'List clients with pagination and filters',
                    'POST /api/clients' => 'Create new client',
                    'GET /api/clients/{uuid}' => 'Get client details',
                    'PUT /api/clients/{uuid}' => 'Update client',
                    'DELETE /api/clients/{uuid}' => 'Delete client',
                ],
                'beneficiaries' => [
                    'GET /api/beneficiaries?client_id={uuid}' => 'List beneficiaries by client',
                    'POST /api/beneficiaries' => 'Create new beneficiary',
                    'GET /api/beneficiaries/{uuid}' => 'Get beneficiary details',
                    'PUT /api/beneficiaries/{uuid}' => 'Update beneficiary',
                    'DELETE /api/beneficiaries/{uuid}' => 'Delete beneficiary',
                ],
                'payment_orders' => [
                    'GET /api/payment-orders' => 'List payment orders with filters',
                    'POST /api/payment-orders' => 'Create payment order with balance validation',
                    'GET /api/payment-orders/{uuid}' => 'Get payment order details',
                ],
                'audit' => [
                    'GET /api/audit/clients/{uuid}/history' => 'Get client transaction history',
                    'GET /api/audit/transactions/{uuid}/{type}' => 'Get specific transaction audit',
                ],
            ],
            'response_format' => [
                'success' => 'boolean - Indicates if the operation was successful',
                'message' => 'string - Descriptive message about the operation',
                'data' => 'object - Response data or null',
                'timestamp' => 'string - ISO 8601 timestamp of the response',
            ],
            'status_codes' => [
                200 => 'OK - Operation successful',
                201 => 'Created - Resource created successfully',
                400 => 'Bad Request - Error in the request',
                404 => 'Not Found - Resource not found',
                422 => 'Unprocessable Entity - Validation or business logic error',
                500 => 'Internal Server Error - Server error',
            ],
        ];

        return GeneralHelper::apiResponse($info, 'API information retrieved successfully');
    }
}
