<?php

use Illuminate\Support\Facades\Route;

Route::prefix('api/chat')->middleware(['web', 'auth'])->group(function () {
    Route::get('/', [\App\LiveChat\Http\Controllers\ChatController::class, 'index']);
    Route::post('/', [\App\LiveChat\Http\Controllers\ChatController::class, 'store']);
    Route::get('/{id}/messages', [\App\LiveChat\Http\Controllers\ChatController::class, 'messages']);
    Route::post('/{id}/messages', [\App\LiveChat\Http\Controllers\ChatController::class, 'sendMessage']);
    Route::post('/{id}/typing', [\App\LiveChat\Http\Controllers\ChatController::class, 'typing']);
    Route::get('/{id}/participants', [\App\LiveChat\Http\Controllers\ChatController::class, 'participants']);
    Route::post('/{id}/read', [\App\LiveChat\Http\Controllers\ChatController::class, 'read']);
});
