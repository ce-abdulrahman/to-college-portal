<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class University extends Model
{
    use HasFactory;

    protected $fillable = ['province_id', 'name', 'name_en', 'lat', 'lng', 'status', 'geojson', 'image'];

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

    public function picture()
    {
        return $this->hasMany(Picture::class);
    }
}
