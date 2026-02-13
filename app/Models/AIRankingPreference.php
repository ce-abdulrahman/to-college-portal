<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AIRankingPreference extends Model
{
    use HasFactory;

    protected $table = 'ai_ranking_preferences';
    protected $primaryKey = 'id';

    protected $fillable = [
        'student_id',
        'consider_personality',
        'include_specific_questions',
        'prefer_nearby_departments',
        'preferred_systems', // JSON: [1,2,3]
        'gender_filter', // JSON: ['نێر','مێ']
        'field_type_filter', // ['زانستی','وێژەیی']
        'province_filter',
        'use_mark_bonus',
        'mark_bonus_enabled',
    ];

    protected $casts = [
        'consider_personality' => 'boolean',
        'include_specific_questions' => 'boolean',
        'prefer_nearby_departments' => 'boolean',
        'preferred_systems' => 'array',
        'gender_filter' => 'array',
        'field_type_filter' => 'array',
        'use_mark_bonus' => 'boolean',
        'mark_bonus_enabled' => 'boolean',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
