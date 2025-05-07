<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Filament\Notifications\Notification;

class EnsureUserHasRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (! Auth::check() || ! Auth::user()->hasRole($role)) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Unauthorized. You do not have the required role.'], 403);
            }

            // Jika user memiliki session tetapi tidak memiliki role yang diperlukan
            if (Auth::check()) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect()->route('filament.custom.login')->with('error', 'Anda tidak memiliki izin untuk mengakses halaman tersebut.');
            }

            return redirect()->route('filament.custom.login');
        }

        return $next($request);
    }
}
