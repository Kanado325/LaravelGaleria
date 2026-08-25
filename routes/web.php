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

Route::delete('/photos/{photo}', [PhotoController::class, 'destroy'])
    ->name('photos.destroy')
    ->middleware('auth');


Route::get('/edit/{photo}', [photoController::class, 'edit'])
    ->name('edit')
    ->middleware('auth');

Route::put('/photos/{photo}', [PhotoController::class, 'update'])
    ->name('photos.update')
    ->middleware('auth');

Route::delete('/photos/{photo}', [PhotoController::class, 'destroy'])
    ->name('photos.destroy')
    ->middleware('auth');

    

require __DIR__.'/auth.php';
