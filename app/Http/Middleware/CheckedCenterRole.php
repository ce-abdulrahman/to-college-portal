<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckedCenterRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Breeze (session) یان Sanctum (token) هەردوو ڕێشەکا پۆش دەکرێن
        $user = $request->user();

        if (!$user || $user->role !== 'center') {
            // API: هەڵەی JSON بدە
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Forbidden (center only)'], 403);
            }
            // Web: گەڕانەوە
            abort(403, 'پیشەی پێویستت نییە (center)');
        }

        return $next($request);
    }
}
