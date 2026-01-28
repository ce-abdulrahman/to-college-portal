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
        'user_id',
        'request_all_departments',
        'request_ai_rank',
        'request_gis',
        'reason',
        'status',
        'admin_notes',
        'admin_id',
        'approved_at'
    ];

    protected $casts = [
        'request_all_departments' => 'boolean',
        'request_ai_rank' => 'boolean',
        'request_gis' => 'boolean',
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
        
        return $types;
    }
    
    public function getRequestTypesStringAttribute()
    {
        return implode('، ', $this->request_types);
    }
    
    // پشکنین ئەگەر هیچ جۆرێک دیاری نەکرابێت
    public function getHasRequestTypesAttribute()
    {
        return $this->request_all_departments || $this->request_ai_rank || $this->request_gis;
    }
}