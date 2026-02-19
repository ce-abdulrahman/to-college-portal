<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestMoreDepartments extends Model
{
    use HasFactory;

    protected $table = 'request_more_departments';

    protected $fillable = [
        'student_id',
        'teacher_id',
        'center_id',
        'user_type',
        'user_id',
        'request_all_departments',
        'request_ai_rank',
        'request_gis',
        'request_queue_hand_department',
        'request_limit_teacher',
        'request_limit_student',
        'approved_limit_teacher',
        'approved_limit_student',
        'reason',
        'receipt_image',
        'status',
        'admin_notes',
        'admin_id',
        'approved_at'
    ];

    protected $casts = [
        'request_all_departments' => 'boolean',
        'request_ai_rank' => 'boolean',
        'request_gis' => 'boolean',
        'request_queue_hand_department' => 'boolean',
        'request_limit_teacher' => 'integer',
        'request_limit_student' => 'integer',
        'approved_limit_teacher' => 'integer',
        'approved_limit_student' => 'integer',
        'approved_at' => 'datetime',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function center()
    {
        return $this->belongsTo(Center::class);
    }

    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isApproved()
    {
        return $this->status === 'approved';
    }

    public function isRejected()
    {
        return $this->status === 'rejected';
    }

    // Accessor بۆ نیشاندانی جۆرەکانی داواکاری
    public function getRequestTypesAttribute()
    {
        $types = [];
        
        if ($this->request_all_departments) {
            $types[] = '٥٠ بەش';
        }
        
        if ($this->request_ai_rank) {
            $types[] = 'سیستەمی AI';
        }
        
        if ($this->request_gis) {
            $types[] = 'نەخشە (GIS)';
        }

        if ($this->request_queue_hand_department) {
            $types[] = 'ڕیزبەندی بەشەکان';
        }

        if ((int) $this->request_limit_teacher > 0) {
            $types[] = 'زیادکردنی سنووری مامۆستا +' . (int) $this->request_limit_teacher;
        }

        if ((int) $this->request_limit_student > 0) {
            $types[] = 'زیادکردنی سنووری قوتابی +' . (int) $this->request_limit_student;
        }
        
        return $types;
    }
    
    public function getRequestTypesStringAttribute()
    {
        return implode('، ', $this->request_types);
    }
    
    // پشکنین ئەگەر هیچ جۆرێک دیاری نەکرابێت
    public function getHasRequestTypesAttribute()
    {
        return $this->request_all_departments
            || $this->request_ai_rank
            || $this->request_gis
            || $this->request_queue_hand_department
            || (int) $this->request_limit_teacher > 0
            || (int) $this->request_limit_student > 0;
    }
}
