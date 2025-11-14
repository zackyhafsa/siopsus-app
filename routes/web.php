<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OperasiExportController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['web', 'auth'])->group(function () {
    Route::get('/admin/operasis/export/excel', [OperasiExportController::class, 'excel'])
        ->name('operasis.export.excel');

    Route::get('/admin/operasis/export/pdf', [OperasiExportController::class, 'pdf'])
        ->name('operasis.export.pdf');
});
