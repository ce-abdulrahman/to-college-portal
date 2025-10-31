<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckedTeacher
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

        if (!$user || $user->role !== 'teacher') {
            // API: هەڵەی JSON بدە
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Forbidden (teacher only)'], 403);
            }
            // Web: گەڕانەوە
            abort(403, 'دەسەڵاتی پێویستت نییە (teacher)');
        }

        return $next($request);
    }
}
