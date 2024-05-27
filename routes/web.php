<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;
use App\Http\Controllers\PaymentController;

Route::get('/pay/{id}', [PaymentController::class, 'pay'])->name('pay');

Route::get('/download/{id}', [PaymentController::class, 'downloadFile'])->name('download.file');

Route::post('/process-payment', [PaymentController::class, 'processPayment'])->name('process-payment');
Route::get('/success-payment', [PaymentController::class, 'processPayment'])->name('success-payment');


Route::post('/borrow-book/{id}', [BookController::class, 'borrowBook'])->name('borrow_book');
Route::post('/return-book/{id}', [BookController::class, 'returnBook'])->name('return_book');
Route::post('/add-comment/{id}', [BookController::class, 'addComment'])->name('add_comment');

Route::get('/dashboard', [BookController::class, 'showAllBooks'])->name('dashboard');
Route::get('/search', [BookController::class, 'showAllBooks'])->name('search');
Route::get('/history', [BookController::class, 'showBookingHistory'])->name('history');
Route::get('/borrowed-books', [BookController::class, 'showBorrowedBooks'])->name('borrowed.books');

Route::get('/user', function () {
    return view('user');
}) ->name('_user');

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
