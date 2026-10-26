<?php

use App\Http\Controllers\Api\DesaController;
use App\Http\Controllers\Api\LaporanController;
use App\Http\Controllers\Api\ProyekController;
use App\Http\Controllers\Api\RingkasanController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::get('/ringkasan', [RingkasanController::class, 'index']);
    Route::get('/proyek', [ProyekController::class, 'index']);
    Route::get('/proyek/{id}', [ProyekController::class, 'show']);
    Route::post('/proyek/{id}/ikuti', [ProyekController::class, 'ikuti']);
    Route::post('/proyek/{id}/laporan', [LaporanController::class, 'store']);
    Route::get('/desa/{id}/skor', [DesaController::class, 'skor']);
});
