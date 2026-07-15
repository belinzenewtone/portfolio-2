<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class AuthController extends Controller
{
    private const MAX_ATTEMPTS    = 5;
    private const LOCKOUT_MINUTES = 15;

    public function showLogin(Request $request)
    {
        if (session('is_admin')) {
            return redirect()->route('admin.dashboard');
        }
        return Inertia::render('Admin/Login');
    }

    public function login(Request $request)
    {
        try {
            return $this->_login($request);
        } catch (\Throwable $e) {
            error_log('[AdminLogin] ' . get_class($e) . ': ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine());
            throw $e;
        }
    }

    private function _login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        // ── 1. Session-based lockout check ────────────────────────────────────
        $lockoutUntil = session('admin_lockout_until');

        if ($lockoutUntil) {
            if (now()->timestamp < $lockoutUntil) {
                $minutesLeft = (int) ceil(($lockoutUntil - now()->timestamp) / 60);
                return back()->withErrors([
                    'password' => "Too many failed attempts. Try again in {$minutesLeft} minute(s).",
                ]);
            }
            // Lockout expired — clear it
            session()->forget(['admin_lockout_until', 'admin_login_attempts']);
        }

        // ── 2. Validate credentials (no hardcoded fallback) ───────────────────
        $adminUsername = config('app.admin_username');
        $adminPassword = config('app.admin_password');

        if (
            $adminUsername &&
            $adminPassword &&
            hash_equals($adminUsername, $request->username) &&
            hash_equals($adminPassword, $request->password)
        ) {
            // ── Success ───────────────────────────────────────────────────────
            session()->forget(['admin_login_attempts', 'admin_lockout_until']);

            // 3. Session regeneration — prevent session fixation
            $request->session()->regenerate();

            session([
                'is_admin'            => true,
                'admin_last_activity' => now()->timestamp,
            ]);

            Log::info('Admin login successful', [
                'ip'       => $request->ip(),
                'username' => $request->username,
                'time'     => now()->toDateTimeString(),
            ]);

            return redirect()->route('admin.dashboard');
        }

        // ── 3. Failed — increment counter & log ───────────────────────────────
        $attempts = session('admin_login_attempts', 0) + 1;
        session(['admin_login_attempts' => $attempts]);

        // 6. Log failed attempt with IP + timestamp
        Log::warning('Admin login failed', [
            'ip'       => $request->ip(),
            'username' => $request->username,
            'attempt'  => $attempts,
            'time'     => now()->toDateTimeString(),
        ]);

        // 4. Lock out after MAX_ATTEMPTS failures
        if ($attempts >= self::MAX_ATTEMPTS) {
            $lockoutUntil = now()->addMinutes(self::LOCKOUT_MINUTES)->timestamp;
            session(['admin_lockout_until' => $lockoutUntil]);
            session()->forget('admin_login_attempts');

            Log::warning('Admin account locked out', [
                'ip'            => $request->ip(),
                'username'      => $request->username,
                'lockout_until' => now()->addMinutes(self::LOCKOUT_MINUTES)->toDateTimeString(),
            ]);

            return back()->withErrors([
                'password' => 'Too many failed attempts. You are locked out for ' . self::LOCKOUT_MINUTES . ' minutes.',
            ]);
        }

        $remaining = self::MAX_ATTEMPTS - $attempts;
        return back()->withErrors([
            'password' => "Incorrect username or password. {$remaining} attempt(s) remaining before lockout.",
        ]);
    }

    public function logout(Request $request)
    {
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home');
    }
}
