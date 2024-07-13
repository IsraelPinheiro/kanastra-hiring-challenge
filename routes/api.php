<?php

use App\Http\Controllers\BankSlipBatchController;
use App\Http\Controllers\BankSlipController;
use App\Http\Controllers\StatusController;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Route;

/*-------------------------------------------------------------------------
| API Routes
|------------------------------------------------------------------------*/

Route::get('/', fn (): RedirectResponse => redirect(route('status')));
Route::get('/status', StatusController::class)->name('status');

Route::prefix('/batches')->controller(BankSlipBatchController::class)->name('batches.')->group(function (): void {
    Route::post('/', 'store')->name('store');
    Route::get('/', 'index')->name('index');
    Route::prefix('/{bankSlipBatch}')->group(function (): void {
        Route::get('/', 'show')->name('show');
        Route::get('/file', 'getFile')->name('file');
        Route::delete('/', 'cancel')->name('cancel');
    });
});

Route::prefix('/bank-slips')->controller(BankSlipController::class)->name('bank-slips.')->group(function (): void {
    Route::get('/', 'index')->name('index');
    Route::get('/{bankSlip}', 'show')->name('show');
    Route::delete('/{bankSlip}', 'cancel')->name('cancel');
});
