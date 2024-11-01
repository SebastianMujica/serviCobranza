<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PhoneController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::resource('phones', PhoneController::class)
    ->only(['index', 'store','create','destroy'])
    ->middleware(['auth', 'verified']);

Route::get('phones/export', [PhoneController::class, 'export']);

Route::resource('phones-database', PhoneController::class)
    ->only(['index'])
    ->middleware(['auth', 'verified']);

    require __DIR__.'/auth.php';
