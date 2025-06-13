<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Role
{    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!$request->user()) {
            abort(403, 'Unauthorized action.');
        }

        // Handle both 'praticien' and 'dentist' roles for backwards compatibility
        $userRole = $request->user()->role;
        if ($userRole === 'praticien') {
            $userRole = 'dentist';
        }

        if (!in_array($userRole, $roles)) {
            abort(403, 'Unauthorized action.');
        }

        return $next($request);
    }
}
