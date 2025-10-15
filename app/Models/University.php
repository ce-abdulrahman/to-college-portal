<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class University extends Model
{
    use HasFactory;

    protected $fillable = ['province_id', 'name', 'lat', 'lng', 'status', 'geojson'];
    
    protected $casts = [
        'geojson' => 'array',
    ];

    public function province()
    {
        return $this->belongsTo(Province::class);
    }

    public function college()
    {
        return $this->hasMany(College::class);
    }
}
