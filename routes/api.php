<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ClientController;
use App\Http\Controllers\Api\BeneficiaryController;
use App\Http\Controllers\Api\PaymentOrderController;
use App\Http\Controllers\Api\AuditController;
use App\Http\Controllers\Api\InfoController;
use App\Http\Controllers\Api\TestController;

/*
|--------------------------------------------------------------------------
| API Routes - Sistema de Pagos
|--------------------------------------------------------------------------
|
| Rutas para la API de gestión de pagos a dispersar por plataformas.
| Incluye endpoints para clientes, beneficiarios, órdenes de pago y auditoría.
|
*/

Route::prefix('clients')->group(function () {
    Route::post('/', [ClientController::class, 'store']);
    Route::get('/', [ClientController::class, 'index']);
    Route::get('/{uuid}', [ClientController::class, 'show']);
    Route::put('/{uuid}', [ClientController::class, 'update']);
    Route::delete('/{uuid}', [ClientController::class, 'destroy']);
});

Route::prefix('beneficiaries')->group(function () {
    Route::post('/', [BeneficiaryController::class, 'store']);
    Route::get('/client/{uuid}', [BeneficiaryController::class, 'index']);
    Route::get('/{uuid}', [BeneficiaryController::class, 'show']);
    Route::put('/{uuid}', [BeneficiaryController::class, 'update']);
    Route::delete('/{uuid}', [BeneficiaryController::class, 'destroy']);
});

Route::prefix('payment-orders')->group(function () {
    Route::post('/', [PaymentOrderController::class, 'store']);
    Route::get('/', [PaymentOrderController::class, 'index']);
    Route::get('/{uuid}', [PaymentOrderController::class, 'show']);
});

Route::prefix('audit')->group(function () {
    Route::get('/clients/{uuid}/history', [AuditController::class, 'getClientAuditHistory']);
    Route::get('/transactions/{uuid}/{transactionType}', [AuditController::class, 'getTransactionAudit']);
});

Route::get('/info', [InfoController::class, 'info']);
Route::get('/test', [TestController::class, 'test']);
