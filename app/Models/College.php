<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class College extends Model
{
    use HasFactory;

    protected $fillable = ['university_id', 'name', 'status'];

    public function university()
    {
        return $this->belongsTo(University::class);
    }

    public function department()
    {
        return $this->hasMany(Department::class);
    }
}
