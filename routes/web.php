<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return view('welcome');
});

// Sign in
Route::get('/login', function () {
    return view('auth/login');
});
Route::post('/login', [AuthController::class, 'login'])->name('login');

//Sign out
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Register
Route::get('/register', function () {
    return view('auth/register');
});
Route::post('/register', [AuthController::class, 'register'])->name('register');

Route::middleware('auth')->prefix('books')->group(function () {
    // VIEW
    Route::get('/', [BookController::class, 'index'])->name('books.index');

    // ADD
    Route::get('/add', [BookController::class, 'add'])->name('books.add');
    Route::post('/store', [BookController::class, 'store'])->name('books.store');

    // UPDATE
    Route::get('edit/{id}', [BookController::class, 'edit'])->name('books.edit');
    Route::put('/{id}', [BookController::class, 'update'])->name('books.update');

    // DELETE
    Route::delete('/{id}', [BookController::class, 'destroy'])->name('books.destroy'); 
});
