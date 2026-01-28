<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckedAdminRole
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        
        $user = Auth::user();
        
        if (!$user->isAdmin()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['message' => 'دەستکاری ناڕەوا'], 403);
            }
            return redirect()->route('admin.dashboard')->with('error', 'تۆ مافی چوونە ژوورەوەی ئەم بەشەت نییە.');
        }
        
        return $next($request);
    }
}