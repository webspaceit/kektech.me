<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth'])->group(function () {
    Route::get('support', [\App\Support\Http\Controllers\MerchantSupportController::class, 'index'])->name('support.index');
    Route::post('support', [\App\Support\Http\Controllers\SupportController::class, 'store'])->name('support.store');
    Route::patch('support/{id}', [\App\Support\Http\Controllers\SupportController::class, 'update'])->name('support.update');
    Route::post('support/{id}/read', [\App\Support\Http\Controllers\SupportController::class, 'markRead'])->name('support.markRead');
    Route::post('support/{id}/messages', [\App\Support\Http\Controllers\SupportController::class, 'addMessage'])->name('support.messages');
});
