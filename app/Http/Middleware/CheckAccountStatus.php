<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAccountStatus
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user) {
            return redirect()->route('login');
        }

        if (!$user->isActive()) {
            abort(403, 'Your account has been disabled. Contact administrator.');
        }

        if ($user->isLocked()) {
            abort(423, 'Your account is locked due to too many failed login attempts. Try again after ' . $user->locked_until->format('H:i:s') . '.');
        }

        return $next($request);
    }
}
