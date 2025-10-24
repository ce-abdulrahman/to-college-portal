<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    protected $fillable = [
        'system_id',
        'province_id',
        'university_id',
        'college_id',
        'name',
        'name_en',
        'local_score',
        'external_score',
        'type',
        'sex',
        'lat', 'lng',
        'image',
        'description',
        'status',
    ];

    protected $casts = [
        'geojson' => 'array',
        'lat' => 'float',
        'lng' => 'float',
        'status' => 'boolean',
    ];

    public function system()
    {
        return $this->belongsTo(System::class);
    }

    public function province()
    {
        return $this->belongsTo(Province::class);
    }

    public function university()
    {
        return $this->belongsTo(University::class);
    }

    public function college()
    {
        return $this->belongsTo(College::class);
    }
}
