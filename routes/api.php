<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TwibbonContributorController;
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

    Route::prefix('c')->name('c.')->group(function () {
        Route::post('createCampaign', [TwibbonContributorController::class, 'createCampaign'])->name('createCampaign');
        Route::get('getCampaigns', [TwibbonContributorController::class, 'getCampaigns'])->name('getCampaigns');
        Route::get('getCampaigns/{id}', [TwibbonContributorController::class, 'getCampaignsById'])->name('getCampaignsById');
        Route::post('addCampaignTwibbon', [TwibbonContributorController::class, 'addCampaignTwibbon'])->name('addCampaignTwibbon');
        Route::delete('removeCampaignTwibbon', [TwibbonContributorController::class, 'removeCampaignTwibbon'])->name('removeCampaignTwibbon');
        Route::delete('deleteCampaign', [TwibbonContributorController::class, 'deleteCampaign'])->name('deleteCampaign');
        Route::put('publishCampaign', [TwibbonContributorController::class, 'publishCampaign'])->name('publishCampaign');
        Route::put('updateCampaign', [TwibbonContributorController::class, 'updateCampaign'])->name('updateCampaign');
    });

});



