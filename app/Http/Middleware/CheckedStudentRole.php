<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckedStudentRole
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        
        $user = Auth::user();
        
        if (!$user->isStudent()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['message' => 'دەستکاری ناڕەوا'], 403);
            }
            return redirect()->route('home')->with('error', 'تۆ قوتابی نیت.');
        }
        
        return $next($request);
    }
}