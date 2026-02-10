<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WorkspaceController; // Import ini
use App\Http\Controllers\ProjectController;   // Import ini
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// Halaman Welcome (Landing Page)
Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

// Group Middleware: Hanya user login yang bisa akses
Route::middleware(['auth', 'verified'])->group(function () {

    // --- WORKSPACE ROUTES ---
    // Dashboard utama: List Workspace
    Route::get('/dashboard', [WorkspaceController::class, 'index'])->name('dashboard'); 
    
    // Create & Store Workspace
    Route::post('/workspaces', [WorkspaceController::class, 'store'])->name('workspaces.store');
    
    // Show Workspace (Halaman Detail yang berisi Project List)
    Route::get('/workspaces/{workspace:slug}', [WorkspaceController::class, 'show'])->name('workspaces.show');

    // --- PROJECT ROUTES ---
    // Create Project di dalam Workspace tertentu
    Route::post('/workspaces/{workspace:slug}/projects', [ProjectController::class, 'store'])->name('projects.store');
    
    // Show Kanban Board (Detail Project)
    Route::get('/workspaces/{workspace:slug}/projects/{project:slug}', [ProjectController::class, 'show'])->name('projects.show');

    // --- PROFILE ---
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';