<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\AuditService;
use App\Helpers\GeneralHelper;
use Illuminate\Http\Request;

/**
 * @tags Audit
 */
class AuditController extends Controller
{
    protected $auditService;

    public function __construct(AuditService $auditService)
    {
        $this->auditService = $auditService;
    }

    /**
     * Get client transaction history
     *
     * Retrieve the complete transaction history for a specific client.
     * Includes all payment orders, balance changes, and related operations.
     */
    public function getClientAuditHistory(Request $request, $clientUuid)
    {
        $limit = $request->get('limit', 50);
        $audits = $this->auditService->getClientAuditHistory($clientUuid, $limit);

        return GeneralHelper::apiResponse($audits);
    }

    /**
     * Get specific transaction audit
     *
     * Retrieve audit information for a specific transaction by related ID and type.
     * Used for detailed transaction tracking and compliance purposes.
     */
    public function getTransactionAudit(Request $request, $relatedId, $transactionType)
    {
        $audit = $this->auditService->getTransactionAudit($relatedId, $transactionType);

        if (!$audit) {
            return GeneralHelper::apiError('Audit not found', 404);
        }

        return GeneralHelper::apiResponse($audit);
    }
}
