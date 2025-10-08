<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    protected $fillable = [
        'college_id',
        'name',
        'local_score',
        'internal_score',
        'type',
        'sex',
        'lat', 'lng',
        'description',
        'status',
    ];

    protected $casts = ['geojson' => 'array'];

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
