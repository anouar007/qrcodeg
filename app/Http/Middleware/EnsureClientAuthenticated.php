<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureClientAuthenticated
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! auth('client')->check()) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthenticated.'], 401);
            }

            return redirect()->route('client.access')->with('warning', 'Please enter your client access code to continue.');
        }

        $client = auth('client')->user();
        if (! $client->isActive()) {
            auth('client')->logout();

            return redirect()->route('client.access')->with('error', 'Your client account is currently inactive. Please contact support.');
        }

        return $next($request);
    }
}
