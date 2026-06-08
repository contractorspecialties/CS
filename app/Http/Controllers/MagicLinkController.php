<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

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

        // PRODUCTION: Standard operational email trigger lines
        // Mail::to($user->email)->send(new MagicLinkNotification($verificationUrl));
        
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