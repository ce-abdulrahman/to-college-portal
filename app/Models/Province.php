<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    use HasFactory;

    protected $table = 'provinces';

    protected $primaryKey = 'id';

    protected $fillable = [
        'name',
        'name_en',
        'status',
        'geojson',
        'image'
    ];

    protected $casts = [
        'geojson' => 'array',
        'lat' => 'float',
        'lng' => 'float',
        'status' => 'boolean',
    ];

    public function university()
    {
        return $this->hasMany(University::class, 'province_id', 'id');
    }

    public function picture()
    {
        return $this->hasMany(Picture::class);
    }
}
