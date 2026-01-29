<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Center extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'address',
        'description',
        'ai_rank',
        'gis',
        'all_departments',
        'referral_code',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function teachers()
    {
        return $this->hasMany(Teacher::class, 'referral_code', 'referral_code');
    }

    public function students()
    {
        return $this->hasMany(Student::class, 'referral_code', 'referral_code');
    }
}
