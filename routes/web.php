<?php

use App\Http\Controllers\AboutController;
use App\Http\Controllers\Admin;
use App\Http\Controllers\AgentsController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Panel\PropertyPrefillController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\ServicesController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/properties', [PropertyController::class, 'index'])->name('properties.index');
Route::get('/properties/{property:slug}', [PropertyController::class, 'show'])->name('properties.show');

Route::get('/about', [AboutController::class, 'show'])->name('about');

Route::get('/services', [ServicesController::class, 'show'])->name('services');

Route::get('/agents', [AgentsController::class, 'index'])->name('agents');

Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{post:slug}', [BlogController::class, 'show'])->name('blog.show');
Route::post('/blog/{post:slug}/comments', [BlogController::class, 'storeComment'])
    ->middleware('auth')
    ->name('blog.comments.store');

Route::get('/contact', [ContactController::class, 'create'])->name('contact.create');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

// Authenticated landing — sends everyone somewhere sensible.
Route::middleware('auth')->get('/dashboard', function () {
    $user = auth()->user();
    if ($user->isAdmin() || $user->isAgent()) {
        return redirect()->route('panel.dashboard');
    }

    return view('dashboard');
})->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Panel — admin + agent.
Route::middleware(['auth', 'role:admin,agent'])
    ->prefix('panel')
    ->name('panel.')
    ->group(function () {
        Route::get('/', [Admin\DashboardController::class, 'index'])->name('dashboard');
        // Registered BEFORE the resource so `properties/prefill` isn't shadowed by `properties/{property}`.
        Route::post('properties/prefill', [PropertyPrefillController::class, 'fetch'])->name('properties.prefill');
        Route::resource('properties', Admin\PropertyController::class)->except(['show']);
        Route::get('inquiries', [Admin\InquiryController::class, 'index'])->name('inquiries.index');
        Route::get('inquiries/{submission}', [Admin\InquiryController::class, 'show'])->name('inquiries.show');
        // Blog admin — index only for this batch. Full CRUD deferred to a future batch.
        Route::get('blog', [Admin\BlogController::class, 'index'])->name('blog.index');
    });

// Admin-only user management.
Route::middleware(['auth', 'role:admin'])
    ->prefix('panel/admin')
    ->name('panel.admin.')
    ->group(function () {
        Route::resource('users', Admin\UserController::class)->only(['index', 'edit', 'update']);
    });

require __DIR__.'/auth.php';
