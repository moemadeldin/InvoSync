<?php

declare(strict_types=1);

use App\Http\Controllers\API\ExternalInvoiceController;
use App\Http\Controllers\API\ExternalProvisionController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')
    ->middleware('internal.sync')
    ->group(function (): void {
        Route::post('/external-invoice', ExternalInvoiceController::class);
        Route::post('/provision-teacher', ExternalProvisionController::class);
    });
