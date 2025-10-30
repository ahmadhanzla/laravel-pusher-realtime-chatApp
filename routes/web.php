<?php

use App\Http\Controllers\ProfileController;
use App\Livewire\Chat;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('login');
});

Route::get('/chat', function () {
    return view('livewire.chat');
})->middleware(['auth', 'verified'])->name('chat');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get("chat" , Chat::class)->name('chat');
});

require __DIR__.'/auth.php';
