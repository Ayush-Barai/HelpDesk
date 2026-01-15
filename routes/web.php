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
Route::get('/tickets/edit/{ticket}',[TicketController::class,'edit'])->middleware(['auth'])->name('tickets.edit');
Route::patch('/tickets/edit/{ticket}',[TicketController::class,'update'])->middleware(['auth'])->name('tickets.update');
Route::get('/tickets/search-similar', [TicketController::class, 'searchSimilar'])->name('tickets.search.similar');

Route::get('/attachments/download/{attachment}',[AttachmentController::class,'download'])->middleware('auth')->name('attachments.download');
Route::get('/attachments/{ticket}',[AttachmentController::class,'attachmentsIndex'])->middleware('auth')->name('tickets.attachments');



require __DIR__.'/auth.php';
