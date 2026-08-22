<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureOwnership
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user || !$user->is_active) {
            abort(403);
        }

        if ($user->isAdmin()) {
            return $next($request);
        }

        $booking = $request->route('booking');

        if ($booking && $booking->organization_id !== $user->organization_id) {
            abort(403, 'Anda tidak memiliki akses ke data ini.');
        }

        return $next($request);
    }
}
