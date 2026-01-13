<?php

use App\Http\Controllers\AttachmentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TicketController;
use GuzzleHttp\Middleware;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/tickets' , [TicketController::class,'index'])->middleware(['auth', 'verified'])->name('tickets');
Route::get('/tickets/create',[TicketController::class,'create'])->middleware(['auth']);
Route::get('/tickets/show/{ticket}',[TicketController::class,'show'])->middleware(['auth'])->name('tickets.show');
Route::post('/tickets/store',[TicketController::class,'store'])->middleware(['auth']);
Route::patch('/tickets/update/{ticket}',[TicketController::class,'update'])->middleware(['auth'])->name('tickets.update');

Route::get('/attachments/{attachment}',[AttachmentController::class,'show'])->middleware('auth');

require __DIR__.'/auth.php';
