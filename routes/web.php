<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PhotoController;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/dashboard', [PhotoController::Class, 'index'])-> name('dashboard');
    Route::post('/photos', [PhotoController::Class, 'store'])->name('photos.store');
});

Route::post('/photos/{photo}/like', [photoController::class, 'like'])
    ->middleware('auth')
    ->name('photos.like');

require __DIR__.'/auth.php';
