<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class MagicLinkController extends Controller
{
    /**
     * Show the clean, simple login screen.
     */
    public function showLogin()
    {
        return view('auth.login');
    }

    /**
     * Handle new signups from the homepage and create the initial account.
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'business_name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'mobile_number' => 'required|string|max:30',
        ], [
            'email.unique' => 'This email address is already connected to an active account.'
        ]);

        // Create the new contractor account record safely
        $user = User::create([
            'name' => $validated['business_name'],
            'email' => $validated['email'],
            'phone' => $validated['mobile_number'],
            'password' => bcrypt(Str::random(32)),   
            'is_admin' => false,
            'is_gc' => false,
            'is_restricted' => false,
        ]);

        // Generate a secure, one-time login token that lasts 30 minutes
        $token = Str::random(64);
        $user->update([
            'magic_link_token' => hash('sha256', $token),
            'magic_link_expires_at' => now()->addMinutes(30), 
        ]);

        $verificationUrl = route('login.verify', ['token' => $token]);

        // Send the account activation email via SendGrid
        Mail::send([], [], function ($message) use ($user, $verificationUrl) {
            $message->to($user->email)
                ->subject('Activate Your Free Workspace | Contractor Specialties')
                ->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'))
                ->html("
                    <div style=\"font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; background-color: #F0F0F0; padding: 40px; text-align: center;\">
                        <div style=\"max-width: 500px; margin: 0 auto; background: #FFFFFF; padding: 32px; border-radius: 24px; border: 1px solid #E2E8F0; text-align: left;\">
                            <h2 style=\"color: #0F2D5A; font-size: 24px; font-weight: 800; margin-bottom: 8px;\">Welcome aboard!</h2>
                            <p style=\"color: #4A5568; font-size: 15px; font-weight: 600; line-height: 1.6; margin-bottom: 24px;\">Your profile for <strong>{$user->name}</strong> has been created. Click below to securely sign in and claim your dashboard.</p>
                            <a href=\"{$verificationUrl}\" style=\"display: block; text-align: center; background-color: #0F2D5A; color: #FFFFFF; font-weight: 800; text-decoration: none; padding: 16px; border-radius: 12px; font-size: 14px; text-transform: uppercase; tracking-spacing: 1px;\">Access Your Dashboard</a>
                            <p style=\"color: #A0AEC0; font-size: 11px; font-weight: 500; text-align: center; margin-top: 24px;\">This login link will automatically expire in 30 minutes.</p>
                        </div>
                    </div>
                ");
        });

        Log::info("ONBOARDING ENGINE: Created New Contractor Account ID {$user->id}. Login Route: {$verificationUrl}");

        return redirect()->back()->with('status', 'Registration successful! Your secure access link has been sent to your inbox. Check your email to claim your dashboard.');
    }

    /**
     * Save or update all the deep business details from the dashboard form.
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'business_name' => 'required|string|max:255',
            'specialty_id' => 'required|exists:specialties,id',
            'phone' => 'required|string|max:30',
            'city' => 'required|string|max:255',
            'state' => 'required|string|size:2',
            'bio' => 'nullable|string',
            
            // Trust & Credibility Fields
            'license_number' => 'nullable|string|max:255',
            'established_year' => 'nullable|integer|min:1900|max:2026',
            'is_insured' => 'nullable|boolean',

            // Service Area Fields
            'service_radius' => 'nullable|integer',
            'service_areas' => 'nullable|string',

            // Business Operations Fields
            'minimum_service_fee' => 'nullable|integer|min:0',
            'hourly_rate' => 'nullable|integer|min:0',
            'crew_size' => 'nullable|integer|min:1|max:100',
        ]);

        // Generate a clean web link name from their business title
        $slug = Str::slug($validated['business_name']);

        // Check if another business already uses this exact web link name
        $slugCollision = User::where('slug', $slug)
            ->where('id', '!=', $user->id)
            ->exists();

        if ($slugCollision) {
            $slug = $slug . '-' . $user->id;
        }

        // Update the contractor's profile details in the database
        $user->update([
            'business_name' => $validated['business_name'],
            'specialty_id' => $validated['specialty_id'],
            'phone' => $validated['phone'],
            'city' => $validated['city'],
            'state' => strtoupper($validated['state']),
            'bio' => $validated['bio'],
            'slug' => $slug,
            
            // Trust & Credibility Fields
            'license_number' => $validated['license_number'],
            'established_year' => $validated['established_year'],
            'is_insured' => $request->has('is_insured'),

            // Service Area Fields
            'service_radius' => $validated['service_radius'],
            'service_areas' => $validated['service_areas'],

            // Business Operations Fields
            'minimum_service_fee' => $validated['minimum_service_fee'],
            'hourly_rate' => $validated['hourly_rate'],
            'crew_size' => $validated['crew_size'],
        ]);

        return redirect()->back()->with('status', 'Your business profile details have been successfully saved and updated!');
    }

    /**
     * Create and email a new secure access link when an existing user tries to sign in.
     */
    public function sendLink(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email|exists:users,email',
        ], [
            'email.exists' => 'No active registration maps to that email address.'
        ]);

        $user = User::where('email', $validated['email'])->firstOrFail();

        $token = Str::random(64);
        $user->update([
            'magic_link_token' => hash('sha256', $token),
            'magic_link_expires_at' => now()->addMinutes(15),
        ]);

        $verificationUrl = route('login.verify', ['token' => $token]);

        Mail::send([], [], function ($message) use ($user, $verificationUrl) {
            $message->to($user->email)
                ->subject('Your Dashboard Secure Access Link')
                ->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'))
                ->html("
                    <div style=\"font-family: Arial, sans-serif; padding: 30px; background: #F0F0F0;\">
                        <div style=\"max-width: 450px; background: #FFFFFF; padding: 25px; border-radius: 16px; margin: 0 auto;\">
                            <h3 style=\"color: #0F2D5A;\">Secure Dashboard Access</h3>
                            <p style=\"font-size: 14px; color: #3C3C3C;\">Click the button below to instantly sign into your control dashboard.</p>
                            <a href=\"{$verificationUrl}\" style=\"display: inline-block; background: #0F2D5A; color: #FFFFFF; padding: 12px 20px; text-decoration: none; border-radius: 8px; font-weight: bold; margin-top: 10px;\">Sign Into Dashboard</a>
                        </div>
                    </div>
                ");
        });
        
        Log::info("SECURITY PROTOCOL: Magic Entry Link Generated for User ID {$user->id}. Destination: {$verificationUrl}");

        return redirect()->back()->with('status', 'A secure entry link has been generated. Check your email to access your dashboard.');
    }

    /**
     * Verify the login link token and log the contractor into their dashboard session.
     */
    public function verifyToken($token)
    {
        $hashedToken = hash('sha256', $token);

        $user = User::where('magic_link_token', $hashedToken)
            ->where('magic_link_expires_at', '>', now())
            ->first();

        if (!$user) {
            return redirect()->route('login')->withErrors([
                'email' => 'The login link has expired or is no longer valid.'
            ]);
        }

        if ($user->is_restricted) {
            return redirect()->route('login')->withErrors([
                'email' => 'This account has been temporarily suspended.'
            ]);
        }

        $user->update([
            'magic_link_token' => null,
            'magic_link_expires_at' => null,
        ]);

        $rememberMe = true;
        Auth::login($user, $rememberMe);

        if ($user->is_admin) {
            return redirect()->route('admin.command-center.index');
        }

        return redirect()->route('dashboard');
    }

    /**
     * Display the public directory profile page for homeowners and search engines.
     */
    public function showPublicProfile($specialty_slug, $user_slug)
    {
        // Find the active contractor using their web link name
        $contractor = User::where('slug', $user_slug)
            ->where('is_restricted', false)
            ->firstOrFail();

        // Safety verification: make sure their trade category matches the web address exactly
        if (!$contractor->specialty || $contractor->specialty->slug !== $specialty_slug) {
            abort(404);
        }

        return view('public-profile', compact('contractor'));
    }

    /**
     * Terminate the authenticated session and flush session caches.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('status', 'You have been successfully logged out of your workspace.');
    }
}