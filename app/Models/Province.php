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
        'system_id',
        'name',
        'status',
    ];

    public function system()
    {
        return $this->belongsTo(System::class);
    }

    public function university()
    {
        return $this->hasMany(University::class, 'province_id', 'id');
    }
}
