<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckedStudentRole
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user || $user->role !== 'student') {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Forbidden (student only)'], 403);
            }

            abort(403, 'پیشەی پێویستت نییە (student)');
        }

        return $next($request);
    }
}
