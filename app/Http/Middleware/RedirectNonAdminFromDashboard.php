<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectNonAdminFromDashboard
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();

        // Jika user sudah login, bukan admin, dan sedang akses dashboard
        if ($user && !$user->isAdmin() && $request->is('admin') && !$request->is('admin/*')) {
            return redirect()->route('filament.admin.resources.operasis.index');
        }

        return $next($request);
    }
}
