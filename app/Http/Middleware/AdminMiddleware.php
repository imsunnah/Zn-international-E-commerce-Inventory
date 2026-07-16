<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && auth()->user()->role === 'admin') {
            if (session('admin_last_activity')) {
                $lastActivity = session('admin_last_activity');
                $lastActivity = $lastActivity instanceof \Carbon\Carbon ? $lastActivity : \Carbon\Carbon::parse($lastActivity);
                if (now()->diffInMinutes($lastActivity, true) >= 180) {
                    auth()->logout();
                    session()->forget('admin_last_activity');
                    return redirect()->route('admin.login')->withErrors([
                        'email' => 'Your session has expired due to 3 hours of inactivity.'
                    ]);
                }
            }
            session(['admin_last_activity' => now()]);
            
            return $next($request);
        }

        return redirect()->route('admin.login')->withErrors([
            'email' => 'You must be an administrator to access this area.'
        ]);
    }
}
