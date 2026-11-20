<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RequirePasswordChange
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user()->must_change_password) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Ganti password awal sebelum melanjutkan.'], 403);
            }

            return redirect()->route('password.edit');
        }

        return $next($request);
    }
}
