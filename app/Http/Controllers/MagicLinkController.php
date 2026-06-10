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
     * Render the custom, minimal authentication intake desk.
     */
    public function showLogin()
    {
        return view('auth.login');
    }

    /**
     * Handle incoming landing page signups and provision new contractor entities.
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'business_name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'mobile_number' => 'required|string|max:30',
        ], [
            'email.unique' => 'This communications vector is already bound to an active workspace node.'
        ]);

        // Provision the user record safely insulating for custom database prefix structures
        $user = User::create([
            'name' => $validated['business_name'],
            'email' => $validated['email'],
            'phone' => $validated['mobile_number'], // Maps cleanly to your schema core
            'password' => bcrypt(Str::random(32)),   // Strict randomized string bypass
            'is_admin' => false,
            'is_gc' => false,
            'is_restricted' => false,
        ]);

        // Generate immediate post-registration cryptographic authentication sequence
        $token = Str::random(64);
        $user->update([
            'magic_link_token' => hash('sha256', $token),
            'magic_link_expires_at' => now()->addMinutes(30), // Extended time for initial onboarding
        ]);

        $verificationUrl = route('login.verify', ['token' => $token]);

        // Dispatches structural raw HTML markup through our active SendGrid API pipeline
        Mail::send([], [], function ($message) use ($user, $verificationUrl) {
            $message->to($user->email)
                ->subject('Activate Your Free Workspace | Contractor Specialties')
                ->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'))
                ->html("
                    <div style=\"font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; background-color: #F0F0F0; padding: 40px; text-align: center;\">
                        <div style=\"max-width: 500px; margin: 0 auto; bg-color: #FFFFFF; background: #FFFFFF; padding: 32px; border-radius: 24px; border: 1px solid #E2E8F0; text-align: left;\">
                            <h2 style=\"color: #0F2D5A; font-size: 24px; font-weight: 800; margin-bottom: 8px;\">Welcome to the Grid.</h2>
                            <p style=\"color: #4A5568; font-size: 15px; font-weight: 600; line-height: 1.6; margin-bottom: 24px;\">Your profile footprint for <strong>{$user->name}</strong> has been successfully initialized. Click below to securely bypass password setup and claim your dashboard.</p>
                            <a href=\"{$verificationUrl}\" style=\"display: block; text-align: center; background-color: #0F2D5A; color: #FFFFFF; font-weight: 800; text-decoration: none; padding: 16px; border-radius: 12px; font-size: 14px; text-transform: uppercase; tracking-spacing: 1px;\">Access Workspace Platform</a>
                            <p style=\"color: #A0AEC0; font-size: 11px; font-weight: 500; text-align: center; margin-top: 24px;\">This token parameters signature vector will automatically expire in 30 minutes.</p>
                        </div>
                    </div>
                ");
        });

        // Local fallback log tracing to facilitate zero-lag system testing right out of Ploi
        Log::info("ONBOARDING ENGINE: Forged New Contractor Node ID {$user->id}. Automated Token Route: {$verificationUrl}");

        return redirect()->back()->with('status', 'Registration finalized! Your activation signature link has been fired via SendGrid. Check your inbox to claim your new workspace.');
    }

    /**
     * Resolve incoming setup parameters and save contractor profiles.
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
            
            // New Trust & Credibility Validation Rules
            'license_number' => 'nullable|string|max:255',
            'established_year' => 'nullable|integer|min:1900|max:2026',
            'is_insured' => 'nullable|boolean',
        ]);

        // Generate a clean public web link name from their business name
        $slug = Str::slug($validated['business_name']);

        // Double check if another business already has this exact name link
        $slugCollision = User::where('slug', $slug)
            ->where('id', '!=', $user->id)
            ->exists();

        if ($slugCollision) {
            $slug = $slug . '-' . $user->id;
        }

        // Save everything to the user record
        $user->update([
            'business_name' => $validated['business_name'],
            'specialty_id' => $validated['specialty_id'],
            'phone' => $validated['phone'],
            'city' => $validated['city'],
            'state' => strtoupper($validated['state']),
            'bio' => $validated['bio'],
            'slug' => $slug,
            
            // New Trust & Credibility Fields
            'license_number' => $validated['license_number'],
            'established_year' => $validated['established_year'],
            // Checkboxes don't send anything if empty, so we use true/false based on its presence
            'is_insured' => $request->has('is_insured'),
        ]);

        return redirect()->back()->with('status', 'Your business profile details have been successfully saved and updated!');
    }

    /**
     * Generate secure authentication token mappings and dispatch entry links.
     */
    public function sendLink(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email|exists:users,email',
        ], [
            'email.exists' => 'No active regional node registration maps to that entry vector.'
        ]);

        // Isolate records through pure Eloquent to guarantee prefix insulation compatibility
        $user = User::where('email', $validated['email'])->firstOrFail();

        // Establish token security criteria parameters
        $token = Str::random(64);
        $user->update([
            'magic_link_token' => hash('sha256', $token),
            'magic_link_expires_at' => now()->addMinutes(15),
        ]);

        $verificationUrl = route('login.verify', ['token' => $token]);

        // Direct transactional email loop transmission
        Mail::send([], [], function ($message) use ($user, $verificationUrl) {
            $message->to($user->email)
                ->subject('Your Dashboard Secure Access Token Link')
                ->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'))
                ->html("
                    <div style=\"font-family: Arial, sans-serif; padding: 30px; background: #F0F0F0;\">
                        <div style=\"max-width: 450px; background: #FFFFFF; padding: 25px; border-radius: 16px; margin: 0 auto;\">
                            <h3 style=\"color: #0F2D5A;\">Secure Workspace Entry Request</h3>
                            <p style=\"font-size: 14px; color: #3C3C3C;\">Click the button below to authorize this session state loop and instantly open your control dashboard portal.</p>
                            <a href=\"{$verificationUrl}\" style=\"display: inline-block; background: #0F2D5A; color: #FFFFFF; padding: 12px 20px; text-decoration: none; border-radius: 8px; font-weight: bold; margin-top: 10px;\">Sign Into Platform</a>
                        </div>
                    </div>
                ");
        });
        
        // INTERNALS: Local fallback logging block to ensure you can access link strings right out of Ploi/storage files
        Log::info("SECURITY PROTOCOL: Magic Entry Link Generated for Node ID {$user->id}. Target Destination: {$verificationUrl}");

        return redirect()->back()->with('status', 'An active cryptographic entry link has been generated. Check your system transmission logs or mailbox to access your workspace.');
    }

    /**
     * Authenticate and bind the incoming session payload if signature hashes check out.
     */
    public function verifyToken($token)
    {
        $hashedToken = hash('sha256', $token);

        $user = User::where('magic_link_token', $hashedToken)
            ->where('magic_link_expires_at', '>', now())
            ->first();

        if (!$user) {
            return redirect()->route('login')->withErrors([
                'email' => 'The authentication signature link has expired or has been voided by a subsequent security sequence.'
            ]);
        }

        // Circuit breaker intercept preventing restricted entries
        if ($user->is_restricted) {
            return redirect()->route('login')->withErrors([
                'email' => 'This node workspace has been temporarily suspended by administrative oversight commands.'
            ]);
        }

        // Consume token to prevent duplicate usage vectors
        $user->update([
            'magic_link_token' => null,
            'magic_link_expires_at' => null,
        ]);

        Auth::login($user, true);

        // Routing architecture redirection loop sorting rules
        if ($user->is_admin) {
            return redirect()->route('admin.command-center.index');
        }

        return redirect()->route('dashboard');
    }
}