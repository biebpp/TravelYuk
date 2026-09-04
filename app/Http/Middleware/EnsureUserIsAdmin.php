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
    public function handle(Request $request, Closure $next, string $role): Response
    {
        $user = $request->user();

        if(!$user) {
            return redirect()->route('login');
        }

        if ($user->role !== $role) {
            return $request->user()->role === 'admin'
                ? redirect()->route('admin.dashboard')
                : redirect()->route('client.dashboard');
        }

        return $next($request);
    }
}
