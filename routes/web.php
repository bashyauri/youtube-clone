<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\TrendingController;
use App\Livewire\UploadVideo;
use App\Livewire\VideoPage;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');
Route::get('/trending', TrendingController::class)->name('trending');
Route::get('/videos/{video:uuid}', VideoPage::class)->name('video.show');

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
