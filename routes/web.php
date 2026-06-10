<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SuperAdminController;
use App\Http\Controllers\MagicLinkController;
use App\Models\Specialty;

// PUBLIC FOOTPRINT: Consumer & Landing Portal
Route::get('/', function () {
    return view('welcome');
});

// PASSWORDSLESS AUTHENTICATION PROTOCOL INTERFACES
Route::middleware(['guest'])->group(function () {
    Route::get('/login', [MagicLinkController::class, 'showLogin'])->name('login');
    Route::post('/login', [MagicLinkController::class, 'sendLink'])->name('login.send');
    
    // REGISTRATION INTAKE: Provisioning Fresh Contractor Nodes
    Route::post('/register', [MagicLinkController::class, 'register'])->name('register');
});

Route::get('/login/verify/{token}', [MagicLinkController::class, 'verifyToken'])->name('login.verify');

// SYSTEM HUB: Multi-Tenant Command & Telemetry Controls
Route::middleware(['auth'])->group(function () {
    
    // STANDARD DISPATCH: Contractor Dashboard Workspace with Dynamic Taxonomy Data
    Route::get('/dashboard', function () {
        $specialties = Specialty::where('is_active', true)
            ->orderBy('sort_order', 'asc')
            ->get();

        return view('dashboard', compact('specialties'));
    })->name('dashboard');

    // PROFILE MANAGEMENT: Binding Active Trades, Regions, and Programmatic SEO Slugs
    Route::post('/profile/update', [MagicLinkController::class, 'updateProfile'])->name('profile.update');

    // FORTIFIED COMMAND OVERLAY: Multi-Tenant Super Admin Panel
    Route::prefix('admin/command-center')
        ->name('admin.command-center.')
        ->group(function () {
            
            // 1. MASTER MONITOR: Core Dashboard Telemetry Deck
            Route::get('/', [SuperAdminController::class, 'index'])->name('index');
            
            // 2. AUDIT VIEW: Deep Entity Telemetry & Node Analysis
            Route::get('/client/{id}', [SuperAdminController::class, 'showClient'])->name('client.show');
            
            // 3. CIRCUIT BREAKER: Universal Operational Access Suppressions
            Route::post('/client/{id}/toggle-status', [SuperAdminController::class, 'toggleStatus'])->name('client.toggle');
            
            // 4. DESIGN CONTROL: Live Sheet Styling Override Adjustments
            Route::post('/client/{id}/update-theme', [SuperAdminController::class, 'updateTheme'])->name('client.theme');
            
        });
});