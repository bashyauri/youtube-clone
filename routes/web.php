<?php

use App\Http\Controllers\HomeController;
use App\Livewire\UploadVideo;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    Route::post('/video/upload', [UploadVideo::class, 'handleChunk'])->name('video.upload');
});
