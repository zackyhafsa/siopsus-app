<?php

use App\Http\Controllers\OperasiExportController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Export routes (admin only check inside controller)
    Route::get('/admin/operasis/export/excel', [OperasiExportController::class, 'excel'])->name('operasis.export.excel');
    Route::get('/admin/operasis/export/pdf', [OperasiExportController::class, 'pdf'])->name('operasis.export.pdf');
});

require __DIR__ . '/auth.php';
