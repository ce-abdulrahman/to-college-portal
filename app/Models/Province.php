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
        'lat' => 'float',
        'lng' => 'float',
    ];

    protected $casts = [
        'geojson' => 'array',
        'status' => 'boolean',
    ];

    public function university()
    {
        return $this->hasMany(University::class, 'province_id', 'id');
    }
}
