<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');

Route::post('register', [AuthController::class, 'register'])->name('register');
Route::post('login', [AuthController::class, 'login'])->name('login');




Route::middleware(['auth:api'])->group(function () {
    Route::post('updateProfile', [AuthController::class, 'updateProfile'])->name('updateProfile');
    Route::get('getProfile', [AuthController::class, 'getProfile'])->name('getProfile');
    Route::post('updatePassword', [AuthController::class, 'updatePassword'])->name('updatePassword');
    Route::delete('softDeleteProfile', [AuthController::class, 'softDeleteProfile'])->name('softDeleteProfile');

});

