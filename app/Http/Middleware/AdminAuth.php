<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AdminAuth
{
    // Auto-logout after 2 hours of inactivity
    private const SESSION_TIMEOUT_SECONDS = 7200;

    public function handle(Request $request, Closure $next)
    {
        if (!session('is_admin')) {
            return redirect()->route('admin.login');
        }

        // ── Session timeout check ─────────────────────────────────────────────
        $lastActivity = session('admin_last_activity');

        if ($lastActivity && (now()->timestamp - $lastActivity) > self::SESSION_TIMEOUT_SECONDS) {
            Log::channel('single')->info('Admin session expired due to inactivity', [
                'ip'   => $request->ip(),
                'time' => now()->toDateTimeString(),
            ]);

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('admin.login')->withErrors([
                'password' => 'Your session has expired after 2 hours of inactivity. Please log in again.',
            ]);
        }

        // Refresh activity timestamp on every admin request
        session(['admin_last_activity' => now()->timestamp]);

        return $next($request);
    }
}
