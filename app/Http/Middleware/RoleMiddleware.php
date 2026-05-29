<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $user = $request->user();

        if (!$user) {
            abort(403, 'Unauthorized.');
        }

        $role = $user->role;

        // 👑 Admin = full system access (ignore all checks)
        if ($role === 'admin') {
            return $next($request);
        }

        // Director = elevated access (finance + technical + director zones)
        $roleMap = [
            'director' => ['director', 'finance', 'technical'],
            'finance' => ['finance'],
            'technical' => ['technical'],
        ];

        $allowed = $roleMap[$role] ?? [];

        // check if route allows at least one matching role
        foreach ($roles as $requiredRole) {
            if (in_array($requiredRole, $allowed)) {
                return $next($request);
            }
        }

        abort(403, 'Unauthorized.');
    }
}
