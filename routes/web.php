<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\ArmadaController;
use App\Http\Controllers\Admin\DriverController;
use App\Http\Controllers\Admin\InvoiceController;
use App\Http\Controllers\Admin\PengirimanController;
use App\Http\Controllers\Admin\PtController;
use App\Http\Controllers\Auth\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', [AuthController::class, 'index'])->name('login-view');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin-dashboard');

    Route::prefix('admin-pengiriman')->name('pengiriman.')->group(function () {
        Route::get('/', [PengirimanController::class, 'index'])->name('index');
        Route::post('/store', [PengirimanController::class, 'store'])->name('store');
        Route::patch('/update/{id}', [PengirimanController::class, 'update'])->name('update');
        Route::delete('/delete/{id}', [PengirimanController::class, 'destroy'])->name('destroy');
        Route::post('/upload-foto/{id}', [PengirimanController::class, 'uploadFoto'])->name('upload-foto');
        Route::delete('/delete-foto/{id}', [PengirimanController::class, 'deleteFoto'])->name('delete-foto');
        Route::get('/fotos/{id}', [PengirimanController::class, 'getFotos'])->name('fotos');
    });

    Route::prefix('admin-invoice')->name('invoice.')->group(function () {
        Route::post('/generate-pdf', [InvoiceController::class, 'generateInvoicePdf'])->name('generate-pdf');
    });

    Route::prefix('admin-pt')->name('pt.')->group(function () {
        Route::get('/', [PtController::class, 'index'])->name('index');
        Route::post('/store', [PtController::class, 'store'])->name('store');
        Route::patch('/update/{id}', [PtController::class, 'update'])->name('update');
        Route::delete('/delete/{id}', [PtController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('admin-armada')->name('armada.')->group(function () {
        Route::get('/', [ArmadaController::class, 'index'])->name('index');
        Route::post('/store', [ArmadaController::class, 'store'])->name('store');
        Route::patch('/update/{id}', [ArmadaController::class, 'update'])->name('update');
        Route::delete('/delete/{id}', [ArmadaController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('admin-driver')->name('driver.')->group(function () {
        Route::get('/', [DriverController::class, 'index'])->name('index');
        Route::post('/store', [DriverController::class, 'store'])->name('store');
        Route::patch('/update/{id}', [DriverController::class, 'update'])->name('update');
        Route::delete('/delete/{id}', [DriverController::class, 'destroy'])->name('destroy');
    });
});
