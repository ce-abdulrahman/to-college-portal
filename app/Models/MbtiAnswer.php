<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MbtiAnswer extends Model
{
    protected $fillable = [
        'user_id',
        'student_id',
        'question_id',
        'score'
    ];

    protected $casts = [
        'score' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function question()
    {
        return $this->belongsTo(MbtiQuestion::class);
    }
}