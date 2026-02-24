<?php

declare(strict_types=1);

use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\SessionController;
use App\Http\Controllers\Customer\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Invoice\DownloadInvoiceController;
use App\Http\Controllers\Invoice\InvoiceController;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Route;

Route::get('/', fn (): Factory|View => view('welcome'));

Route::middleware('auth')->group(function (): void {
    Route::delete('/logout', [SessionController::class, 'destroy'])->name('logout');

    Route::prefix('dashboard')->group(function (): void {
        Route::get('/', DashboardController::class)->name('dashboard');
        Route::resources([
            'invoices' => InvoiceController::class,
            'customers' => CustomerController::class,
        ]);
        Route::get('/invoices/{invoice}/download', DownloadInvoiceController::class)->name('invoices.download');
    });
});
Route::prefix('auth')
    ->middleware('guest')
    ->group(function (): void {
        Route::controller(RegisterController::class)->group(function (): void {
            Route::get('/register', 'create')->name('register');
            Route::post('/register', 'store')->name('register.post')->middleware('throttle:auth');
        });
        Route::controller(SessionController::class)->group(function (): void {
            Route::get('/login', 'create')->name('login');
            Route::post('/login', 'store')->name('login.post')->middleware('throttle:auth');
        });
    });
