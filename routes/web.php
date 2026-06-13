<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SuperAdminController;
use App\Http\Controllers\MagicLinkController;
use App\Http\Controllers\EstimateController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\DispatchController;
use App\Http\Controllers\WorkerController;
use App\Http\Controllers\HomeownerPortalController;
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

// Public Homeowner Portal: View and Approve Project Estimates (Frictionless Touch Sign Canopy)
Route::get('/estimates/{token}', [HomeownerPortalController::class, 'show'])->name('estimates.public.show');
Route::post('/estimates/{token}/status', [HomeownerPortalController::class, 'approve'])->name('estimates.public.status');


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
    
    // 1. Command Center Home Overview Page
    Route::get('/dashboard', function () {
        $recentEstimates = Estimate::where('user_id', auth()->id())
            ->where('status', '!=', 'archived')
            ->latest()
            ->take(3)
            ->get();

        $actionAlerts = Estimate::where('user_id', auth()->id())
            ->whereIn('status', ['approved', 'declined'])
            ->latest()
            ->get();

        return view('dashboard', compact('recentEstimates', 'actionAlerts'));
    })->name('dashboard');

    // 2. Dedicated Public Profile Settings Page
    Route::get('/dashboard/profile', function () {
        $specialties = Specialty::where('is_active', true)
            ->orderBy('sort_order', 'asc')
            ->get();

        return view('profile', compact('specialties'));
    })->name('dashboard.profile');

    // 3. Dedicated Estimates Management Page
    Route::get('/dashboard/estimates', function () {
        $estimates = Estimate::where('user_id', auth()->id())
            ->where('status', '!=', 'archived')
            ->latest()
            ->get();

        return view('estimates', compact('estimates'));
    })->name('dashboard.estimates');

    // 4. Dedicated Invoices Management Page
    Route::get('/dashboard/invoices', [InvoiceController::class, 'index'])->name('dashboard.invoices');

    // 5. Dedicated Crew Dispatch Scheduler Page (Legacy Layout Support)
    Route::get('/dashboard/scheduler', [ScheduleController::class, 'index'])->name('dashboard.scheduler');

    // 5b. Unified Visual Dispatch Matrix Engine (Sprint 2 Interface Hooks)
    Route::get('/dashboard/dispatch', [DispatchController::class, 'index'])->name('dashboard.dispatch');
    Route::post('/dashboard/dispatch/assign', [DispatchController::class, 'assign'])->name('dashboard.dispatch.assign');

    // 5c. Field Worker Mobile Cockpit Portal (Sprint 4 Infrastructure Hooks)
    Route::get('/worker/dashboard', [WorkerController::class, 'index'])->name('worker.dashboard');
    Route::post('/worker/checkpoint/{id}/toggle', [WorkerController::class, 'toggleCheckpoint'])->name('worker.checkpoint.toggle');
    Route::post('/worker/appointment/{id}/upload', [WorkerController::class, 'uploadPhoto'])->name('worker.upload-photo');

    // 6. Media Markup Studio Workspace Endpoints
    Route::get('/dashboard/estimates/{id}/markup', [EstimateController::class, 'showMarkup'])->name('estimates.markup');
    Route::post('/dashboard/estimates/{id}/markup', [EstimateController::class, 'storeMarkup'])->name('estimates.markup.store');
    Route::post('/dashboard/attachments/{id}/toggle-visibility', [EstimateController::class, 'toggleAttachmentVisibility'])->name('attachments.toggle-visibility');

    // 7. Save Profile Changes Form Submission Action
    Route::post('/profile/update', [MagicLinkController::class, 'updateProfile'])->name('profile.update');

    // 8. Estimate Lifecycle Interceptors (CRUDA Suite)
    Route::post('/estimates', [EstimateController::class, 'store'])->name('estimates.store');
    Route::post('/estimates/{id}/convert', [EstimateController::class, 'convertToInvoice'])->name('estimates.convert');
    Route::post('/estimates/{id}/archive', [EstimateController::class, 'archive'])->name('estimates.archive');
    Route::delete('/estimates/{id}', [EstimateController::class, 'destroy'])->name('estimates.destroy');

    // 9. Invoice Lifecycle Interceptors (CRUDA Actions Suite)
    Route::post('/invoices/{id}', [InvoiceController::class, 'store'])->name('invoices.store');
    Route::patch('/invoices/{id}', [InvoiceController::class, 'update'])->name('invoices.update');
    Route::post('/invoices/{id}/paid', [InvoiceController::class, 'markAsPaid'])->name('invoices.paid');
    Route::post('/invoices/{id}/archive', [InvoiceController::class, 'archive'])->name('invoices.archive');
    Route::delete('/invoices/{id}', [InvoiceController::class, 'destroy'])->name('invoices.destroy');

    // 10. Scheduler Lifecycle Interceptors (Actions Suite)
    Route::post('/schedules', [ScheduleController::class, 'store'])->name('schedules.store');
    Route::delete('/schedules/{id}', [ScheduleController::class, 'destroy'])->name('schedules.destroy');

    // 11. Secure Application Session Logoff Core Interceptor
    Route::post('/logout', [MagicLinkController::class, 'logout'])->name('logout');


    // =========================================================================
    // SUPER ADMIN CONTROL PANEL OVERLAY
    // =========================================================================
    Route::prefix('admin/command-center')
        ->name('admin.command-center.')
        ->group(function () {
            Route::get('/', [SuperAdminController::class, 'index'])->name('index');
            Route::get('/client/{id}', [SuperAdminController::class, 'showClient'])->name('client.show');
            Route::post('/client/{id}/toggle-status', [SuperAdminController::class, 'toggleStatus'])->name('client.toggle');
            Route::post('/client/{id}/update-theme', [SuperAdminController::class, 'updateTheme'])->name('client.theme');
        });
});