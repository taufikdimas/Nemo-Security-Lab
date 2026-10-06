<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ApiTokenMiddleware
{
    /**
     * Token based authentication for the internal asset and incident feeds.
     * Callers present the token in the X-API-Token header or, for legacy
     * integrators that cannot set headers, as a query parameter.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->header('X-API-Token') ?? $request->query('token');

        if (! is_string($token) || $token === '') {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        $user = User::where('api_token', $token)->first();

        if (! $user) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        Auth::login($user);

        return $next($request);
    }
}
