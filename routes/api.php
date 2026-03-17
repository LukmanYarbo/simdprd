<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Resources\AnggotaResource;
use App\Models\Anggota;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/anggota', function () {
        return AnggotaResource::collection(Anggota::with(['statusKeanggotaan', 'jabatan'])->get());
    });
});
