<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SuperAdminController;
use App\Http\Controllers\MagicLinkController;
use App\Http\Controllers\EstimateController;
use App\Models\Specialty;

// =========================================================================
// PUBLIC ROUTES (Anyone can view)
// =========================================================================

// Public Homepage
Route::get('/', function () {
    return view('welcome');
});

// Public Contractor Directory Profiles (SEO Landing Pages)
Route::get('/pros/{specialty_slug}/{user_slug}', [MagicLinkController::class, 'showPublicProfile'])->name('profile.public');


// =========================================================================
// GUEST ROUTES (Only for logged-out users)
// =========================================================================
Route::middleware(['guest'])->group(function () {
    // Secure Login Screen
    Route::get('/login', [MagicLinkController::class, 'showLogin'])->name('login');
    Route::post('/login', [MagicLinkController::class, 'sendLink'])->name('login.send');
    
    // New Contractor Registration Form Submission
    Route::post('/register', [MagicLinkController::class, 'register'])->name('register');
});

// Verify Magic Login Link and Log the User In
Route::get('/login/verify/{token}', [MagicLinkController::class, 'verifyToken'])->name('login.verify');


// =========================================================================
// AUTHENTICATED ROUTES (Must be logged in)
// =========================================================================
Route::middleware(['auth'])->group(function () {
    
    // Main Contractor Dashboard
    Route::get('/dashboard', function () {
        $specialties = Specialty::where('is_active', true)
            ->orderBy('sort_order', 'asc')
            ->get();

        return view('dashboard', compact('specialties'));
    })->name('dashboard');

    // Save Contractor Profile Details from Dashboard Form
    Route::post('/profile/update', [MagicLinkController::class, 'updateProfile'])->name('profile.update');

    // Save and Create a New Project Estimate (CPP Suite)
    Route::post('/estimates', [EstimateController::class, 'store'])->name('estimates.store');

    // Super Admin Control Panel
    Route::prefix('admin/command-center')
        ->name('admin.command-center.')
        ->group(function () {
            
            // 1. Admin Home Overview Page
            Route::get('/', [SuperAdminController::class, 'index'])->name('index');
            
            // 2. View Individual Contractor Account Details
            Route::get('/client/{id}', [SuperAdminController::class, 'showClient'])->name('client.show');
            
            // 3. Suspend or Activate Contractor Account Access
            Route::post('/client/{id}/toggle-status', [SuperAdminController::class, 'toggleStatus'])->name('client.toggle');
            
            // 4. Customize/Override Contractor Dashboard Colors
            Route::post('/client/{id}/update-theme', [SuperAdminController::class, 'updateTheme'])->name('client.theme');
            
        });
});