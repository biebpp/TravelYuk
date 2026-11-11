<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$role): Response
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->route('login');
        }

        if (in_array($user->role, $role, true)) {
            return $next($request);
        }

        return match ($user->role) {
            'superadmin' => redirect()->route('admin.users.dashboard'),
            'admin' => redirect()->route('admin.dashboard'),
            default => redirect()->route('client.dashboard'),
        };
    }
}
