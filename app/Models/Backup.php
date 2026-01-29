<?php
// [file name]: Backup.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Backup extends Model
{
    protected $fillable = [
        'name',
        'file_path', // دڵنیابە کە ئەمە لێرەیە
        'database_type',
        'source_db',
        'target_db',
        'tables_count',
        'records_count',
        'file_size',
        'notes',
        'status'
    ];

    protected $casts = [
        'file_size' => 'float',
    ];

    // Accessor بۆ وەرگرتنی ڕێگەی تەواوی فایل
    public function getFullPathAttribute()
    {
        return storage_path("app/backups/{$this->file_path}");
    }
    
    // Accessor بۆ وەرگرتنی ڕێگەی Public
    public function getPublicPathAttribute()
    {
        // ئەگەر لینکی سمبۆلیک بوونی هەبوو
        if (file_exists(public_path("storage/backups/{$this->file_path}"))) {
            return asset("storage/backups/{$this->file_path}");
        }
        
        return null;
    }
}