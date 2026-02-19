<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    use HasFactory;

    protected $table = 'teachers';
    protected $primaryKey = 'id';

    protected $fillable = [
        'user_id',
        'referral_code',
        'province',
        'ai_rank',
        'gis',
        'all_departments',
        'queue_hand_department',
        'limit_student',
    ];

    protected $casts = [
        'ai_rank' => 'boolean',
        'gis' => 'boolean',
        'all_departments' => 'boolean',
        'queue_hand_department' => 'boolean',
        'limit_student' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function students()
    {
        return $this->hasMany(Student::class, 'referral_code', 'rand_code');
    }

}
