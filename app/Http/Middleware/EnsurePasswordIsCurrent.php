<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsurePasswordIsCurrent
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && $user->passwordRequiresChange() && ! $request->routeIs(
            'settings.password.required',
            'settings.password.update',
            'logout'
        )) {
            return redirect()->route('settings.password.required');
        }

        return $next($request);
    }
}
