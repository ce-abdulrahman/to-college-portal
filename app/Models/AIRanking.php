<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AIRanking extends Model
{
    use HasFactory;

    protected $table = 'ai_rankings';
    protected $primaryKey = 'id';

    protected $fillable = [
        'student_id',
        'department_id',
        'rank',
        'result_rank',
    ];

    protected $casts = [
        'rank' => 'integer',
        'result_rank' => 'integer',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}
