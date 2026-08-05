<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        try {
            if (Auth::attempt($credentials, $request->boolean('remember'))) {
                $request->session()->regenerate();
                return redirect()->intended(route('admin.dashboard'));
            }
        } catch (\RuntimeException $e) {
            // Stored hash is not a valid bcrypt/argon hash (e.g. legacy MD5).
            // Attempt a legacy upgrade: if the supplied password matches the
            // stored legacy hash, re-hash it properly and log the user in.
            $user = \App\Models\User::where('email', $credentials['email'])->first();

            if ($user && $this->matchesLegacyHash($request->password, $user->password)) {
                $user->update(['password' => Hash::make($request->password)]);
                Auth::login($user);
                $request->session()->regenerate();

                return redirect()->intended(route('admin.dashboard'));
            }
        }

        return back()->withErrors([
            'email' => __('auth.failed'),
        ])->onlyInput('email');
    }

    /**
     * Check a plain-text password against a legacy (non-bcrypt) hash.
     *
     * @return bool
     */
    protected function matchesLegacyHash(string $password, string $storedHash): bool
    {
        // MD5 (32-char hex) — common in manually imported accounts.
        if (preg_match('/^[a-f0-9]{32}$/i', $storedHash)) {
            return hash_equals(strtolower($storedHash), md5($password));
        }

        // SHA-1 (40-char hex) and SHA-256 (64-char hex) legacy hashes.
        if (preg_match('/^[a-f0-9]{40}$/i', $storedHash)) {
            return hash_equals(strtolower($storedHash), sha1($password));
        }

        if (preg_match('/^[a-f0-9]{64}$/i', $storedHash)) {
            return hash_equals(strtolower($storedHash), hash('sha256', $password));
        }

        // Plain-text fallback (only for stored values that are clearly not hashed).
        if (! str_starts_with($storedHash, '$')) {
            return hash_equals($storedHash, $password);
        }

        return false;
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
