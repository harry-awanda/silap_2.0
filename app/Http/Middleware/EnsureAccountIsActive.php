<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureAccountIsActive {
  public function handle(Request $request, Closure $next): Response {
    if ($request->user()?->disabled_at !== null) {
      Auth::logout();
      $request->session()->invalidate();
      $request->session()->regenerateToken();

      if ($request->expectsJson()) {
        return response()->json(['message' => 'Akun Anda dinonaktifkan. Hubungi administrator.'], 401);
      }

      return redirect()->route('login')->withErrors(['username' => 'Akun Anda dinonaktifkan. Hubungi administrator.']);
    }

    return $next($request);
  }
}
