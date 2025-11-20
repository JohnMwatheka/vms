<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
Route::middleware('auth')->group(function () {
    Route::post('/switch-role', function (Request $request) {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $request->validate([
            'role' => 'required|in:initiator,vendor,checker,procurement,legal,finance,directors'
        ]);

        // This is the BEST way — removes all current roles and assigns exactly one
        $user->syncRoles($request->role);

        return redirect()->back()->with('success', 'You are now acting as: ' . ucfirst($request->role));
    })->name('switch.role');
});
require __DIR__.'/auth.php';
