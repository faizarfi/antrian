<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasRole
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (! Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $user = Auth::user();

        if (! $user->is_active) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')->with('error', 'Akun Anda dinonaktifkan. Hubungi Administrator.');
        }

        // Admin can access everything
        if ($user->role === 'admin') {
            return $next($request);
        }

        // If specific roles required, check if user's role matches any
        if (! in_array($user->role, $roles)) {
            abort(403, 'Akses tidak diizinkan. Halaman ini memerlukan hak akses khusus.');
        }

        return $next($request);
    }
}
