<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AIAnswer extends Model
{
    use HasFactory;

    protected $table = 'ai_answers';

    protected $fillable = [
        'student_id',
        'question_id',
        'answer',
        'score',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function question()
    {
        return $this->belongsTo(AIQuestion::class);
    }
}

