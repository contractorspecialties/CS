<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SuperAdminController;
use App\Http\Controllers\MagicLinkController;
use App\Http\Controllers\EstimateController;
use App\Models\Specialty;
use App\Models\Estimate;

// =========================================================================
// PUBLIC ROUTES (Anyone can view)
// =========================================================================

// Public Homepage
Route::get('/', function () {
    return view('welcome');
});

// Public Contractor Directory Profiles (SEO Landing Pages)
Route::get('/pros/{specialty_slug}/{user_slug}', [MagicLinkController::class, 'showPublicProfile'])->name('profile.public');

// Public Homeowner Portal: View and Approve Project Estimates (CPP Suite)
Route::get('/estimates/{token}', [EstimateController::class, 'showPublic'])->name('estimates.public.show');
Route::post('/estimates/{token}/status', [EstimateController::class, 'updateStatus'])->name('estimates.public.status');


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
    
    // 1. Command Center Home Overview Page (Birds-Eye Telemetry Deck)
    Route::get('/dashboard', function () {
        $recentEstimates = Estimate::where('user_id', auth()->id())
            ->where('status', '!=', 'archived')
            ->latest()
            ->take(3)
            ->get();

        return view('dashboard', compact('recentEstimates'));
    })->name('dashboard');

    // 2. Dedicated Public Profile Settings Page
    Route::get('/dashboard/profile', function () {
        $specialties = Specialty::where('is_active', true)
            ->orderBy('sort_order', 'asc')
            ->get();

        return view('profile', compact('specialties'));
    })->name('dashboard.profile');

    // 3. Dedicated Estimates and CPP Tool Suite Page
    Route::get('/dashboard/estimates', function () {
        // Exclude archived items from active workspace views
        $estimates = Estimate::where('user_id', auth()->id())
            ->where('status', '!=', 'archived')
            ->latest()
            ->get();

        return view('estimates', compact('estimates'));
    })->name('dashboard.estimates');

    // 4. Save Profile Changes Form Submission Action
    Route::post('/profile/update', [MagicLinkController::class, 'updateProfile'])->name('profile.update');

    // 5. Core Proposal Lifecycle Interceptors (CRUDA Suite)
    Route::post('/estimates', [EstimateController::class, 'store'])->name('estimates.store');
    Route::post('/estimates/{id}/convert', [EstimateController::class, 'convertToInvoice'])->name('estimates.convert');
    Route::post('/estimates/{id}/archive', [EstimateController::class, 'archive'])->name('estimates.archive');
    Route::delete('/estimates/{id}', [EstimateController::class, 'destroy'])->name('estimates.destroy');


    // =========================================================================
    // SUPER ADMIN CONTROL PANEL OVERLAY
    // =========================================================================
    Route::prefix('admin/command-center')
        ->name('admin.command-center.')
        ->group(function () {
            
            // Admin Home Overview Page
            Route::get('/', [SuperAdminController::class, 'index'])->name('index');
            
            // View Individual Contractor Account Details
            Route::get('/client/{id}', [SuperAdminController::class, 'showClient'])->name('client.show');
            
            // Suspend or Activate Contractor Account Access
            Route::post('/client/{id}/toggle-status', [SuperAdminController::class, 'toggleStatus'])->name('client.toggle');
            
            // Customize/Override Contractor Dashboard Colors
            Route::post('/client/{id}/update-theme', [SuperAdminController::class, 'updateTheme'])->name('client.theme');
            
        });
});