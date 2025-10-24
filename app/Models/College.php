<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class College extends Model
{
    use HasFactory;

    protected $fillable = ['university_id', 'name', 'name_en', 'lat', 'lng', 'status', 'geojson', 'image'];

    protected $casts = [
        'geojson' => 'array',
        'lat' => 'float',
        'lng' => 'float',
        'status' => 'boolean',
    ];

    public function university()
    {
        return $this->belongsTo(University::class);
    }

    public function department()
    {
        return $this->hasMany(Department::class);
    }

    public function picture()
    {
        return $this->hasMany(Picture::class);
    }
}
