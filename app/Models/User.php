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
        'name', 'code', 'password', 'role', 'status', 'phone', 'rand_code',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'code' => 'integer',
        'rand_code' => 'integer',
        'password' => 'hashed',
    ];

    public function getAuthIdentifierName(): string
    {
        return 'code';
    }

    public function teacher()
    {
        return $this->hasOne(Teacher::class);
    }

    public function student()
    {
        return $this->hasOne(Student::class);
    }

    public function center()
    {
        return $this->hasOne(Center::class);
    }
    
    public function isCenter()
    {
        return $this->role === 'center';
    }
    
    public function isStudent()
    {
        return $this->role === 'student';
    }
    
    public function isAdmin()
    {
        return $this->role === 'admin';
    }
    
    // پەیوەندی بە وەڵامەکانی MBTIەوە
    public function mbtiAnswers()
    {
        return $this->hasMany(MbtiAnswer::class);
    }
}