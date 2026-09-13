<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BagmawaOnly
{
    private const ALLOWED_EMAIL = 'bagmawa@ums.ac.id';

    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user || !$user->is_active) {
            abort(403);
        }

        if ($user->email !== self::ALLOWED_EMAIL) {
            abort(403, 'Hanya Bagmawa yang dapat mengakses halaman ini.');
        }

        return $next($request);
    }
}
