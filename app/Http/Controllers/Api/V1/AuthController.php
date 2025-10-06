<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $cred = $request->validate([
            'code'     => ['required','string'], // یان 'email'
            'password' => ['required','string'],
            'device'   => ['nullable','string']
        ]);

        // ئەگەر login بە 'code' ـە:
        if (!auth()->attempt(['code' => $cred['code'], 'password' => $cred['password']])) {
            throw ValidationException::withMessages(['code' => 'زانیاریەکان هەڵەیە!']);
        }

        $user = $request->user();
        $token = $user->createToken($cred['device'] ?? 'flutter')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user'  => [
                'id' => $user->id,
                'name' => $user->name,
                'role' => $user->role,
            ]
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()?->delete();
        return response()->json(['message' => 'Logged out']);
    }
}
