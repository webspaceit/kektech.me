<?php

use Illuminate\Support\Facades\Route;

Route::prefix('api/chat/guest')->middleware('web')->group(function () {
    Route::post('/start', [\App\LiveChat\Http\Controllers\GuestChatController::class, 'start']);
    Route::get('/{id}/messages', [\App\LiveChat\Http\Controllers\GuestChatController::class, 'messages']);
    Route::post('/{id}/messages', [\App\LiveChat\Http\Controllers\GuestChatController::class, 'sendMessage']);
});
