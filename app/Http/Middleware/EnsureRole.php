<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureRole
{
    public function handle(Request $request, Closure $next, string ...$slugs): Response
    {
        $user = $request->user();

        if (!$user || !$user->is_active) {
            abort(403);
        }

        if (!$user->role || !in_array($user->role->slug, $slugs)) {
            abort(403, 'Akses tidak diizinkan.');
        }

        return $next($request);
    }
}
