<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SuperAdminController;
use App\Http\Controllers\MagicLinkController;

// PUBLIC FOOTPRINT: Consumer & Landing Portal
Route::get('/', function () {
    return view('welcome');
});

// PASSWORDSLESS AUTHENTICATION PROTOCOL INTERFACES
Route::middleware(['guest'])->group(function () {
    Route::get('/login', [MagicLinkController::class, 'showLogin'])->name('login');
    Route::post('/login', [MagicLinkController::class, 'sendLink'])->name('login.send');
});

Route::get('/login/verify/{token}', [MagicLinkController::class, 'verifyToken'])->name('login.verify');

// SYSTEM HUB: Multi-Tenant Command & Telemetry Controls
Route::middleware(['auth'])->group(function () {
    
    // STANDARD DISPATCH: Contractor Dashboard Workspace
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

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