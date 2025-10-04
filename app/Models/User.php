<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'code',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        // Remove email_verified_at if you don't use emails
        'code' => 'integer',          // ✅ make code an integer
        'password' => 'hashed',
    ];

    /**
     * Tell Laravel which column is the auth identifier (instead of 'email')
     */
    public function getAuthIdentifierName(): string
    {
        return 'code';                // ✅ use 'code' for auth
    }
}
