<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AIQuestion extends Model
{
    use HasFactory;

    protected $table = 'ai_questions';

    protected $fillable = [
        'question_ku',
        'question_en',
        'category',
        'options',
        'weight',
        'department_weights',
        'order',
        'status',
    ];

    protected $casts = [
        'options' => 'array',
        'department_weights' => 'array',
        'status' => 'boolean',
    ];

    public function answers()
    {
        return $this->hasMany(AIAnswer::class, 'question_id');
    }
}
