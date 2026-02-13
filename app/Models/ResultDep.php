<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResultDep extends Model
{
    use HasFactory;

    protected $table = 'result_deps';
    protected $primaryKey = 'id';

    protected $fillable = [
        'user_id',
        'student_id',
        'department_id',
        'rank',
        'result_rank',
    ];

    protected $casts = [
        'rank' => 'integer',
        'result_rank' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}
