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
        'province',
        'description',
        'ai_rank',
        'gis',
        'all_departments',
        'queue_hand_department',
        'limit_teacher',
        'limit_student',
        'referral_code',
    ];

    protected $casts = [
        'ai_rank' => 'boolean',
        'gis' => 'boolean',
        'all_departments' => 'boolean',
        'queue_hand_department' => 'boolean',
        'limit_teacher' => 'integer',
        'limit_student' => 'integer',
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
