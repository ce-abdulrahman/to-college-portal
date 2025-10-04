<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class System extends Model
{
    use HasFactory;

    protected $table = 'systems';

    protected $primaryKey = 'id';

    protected $fillable = ['name', 'status'];

    public function provinces()
    {
        return $this->hasMany(Province::class, 'system_id', 'id');
    }

}
