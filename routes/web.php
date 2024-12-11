<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PhoneController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PhoneNumberController;

Route::get('/', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/progress', [PhoneController::class, 'progress']);
    Route::get('/download/{file_name}', [PhoneController::class, 'download']);
    Route::get('phones/export', [PhoneController::class, 'export']);
    Route::post('phones-database/import', [PhoneNumberController::class, 'import']);
    Route::resource('phones', PhoneController::class)
    ->only(['index', 'store','create','destroy'])
    ->middleware(['auth', 'verified']);
    Route::resource('phones-database', PhoneNumberController::class)
    ->only(['index','import'])
    ->middleware(['auth', 'verified']);
    Route::get('/phones-database/create', [PhoneNumberController::class , 'create'])->name('database.create');
    Route::get('/batch/{batchId}', [PhoneController::class , 'batch'])->name('batch');
});



    require __DIR__.'/auth.php';
