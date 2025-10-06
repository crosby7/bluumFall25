<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Patient;
use Symfony\Component\HttpFoundation\Response;

class DevImpersonate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // If patient is already authenticated (e.g., in tests via actingAs), skip impersonation
        if (Auth::check() || $request->user()) {
            return $next($request);
        }

        // For development: automatically authenticate as dev patient
        // TODO: Remove this middleware in production and use proper auth:sanctum
        $patient = Patient::where('email', 'dev@example.com')->first();

        if ($patient) {
            Auth::setUser($patient);
            $request->setUserResolver(fn () => $patient);
            return $next($request);
        }

        // No patient found, return unauthorized
        return response()->json(['message' => 'Unauthenticated.'], 401);
    }
}
