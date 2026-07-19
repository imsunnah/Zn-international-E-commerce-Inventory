<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SessionInactivityMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check()) {
            if (session('user_last_activity')) {
                $lastActivity = session('user_last_activity');
                $lastActivity = $lastActivity instanceof \Carbon\Carbon ? $lastActivity : \Carbon\Carbon::parse($lastActivity);
                $timeoutMinutes = config('session.lifetime', 120);

                if (now()->diffInMinutes($lastActivity, true) >= $timeoutMinutes) {
                    auth()->logout();
                    session()->forget('user_last_activity');
                    session()->invalidate();
                    session()->regenerateToken();

                    // Check if route 'home' exists, otherwise redirect to '/'
                    if (\Illuminate\Support\Facades\Route::has('home')) {
                        return redirect()->route('home')->withErrors([
                            'session_expired' => 'Your session has expired due to ' . $timeoutMinutes . ' minutes of inactivity.'
                        ]);
                    }
                    return redirect('/')->withErrors([
                        'session_expired' => 'Your session has expired due to ' . $timeoutMinutes . ' minutes of inactivity.'
                    ]);
                }
            }
            session(['user_last_activity' => now()]);
        }

        return $next($request);
    }
}
