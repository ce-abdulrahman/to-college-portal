<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class Department extends Model
{
    use HasFactory;

    public const MIN_VISIBLE_LOCAL_SCORE = 50;
    public const MIN_VISIBLE_YEARS = 1;

    protected $fillable = [
        'system_id',
        'province_id',
        'university_id',
        'college_id',
        'name',
        'name_en',
        'local_score',
        'external_score',
        'type',
        'sex',
        'description',
        'image'
    ];

    protected $casts = [
        'geojson' => 'array',
        'lat' => 'float',
        'lng' => 'float',
        'status' => 'boolean',
    ];

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

    public function collection()
    {
        return Department::with(['system', 'province', 'university', 'college'])->get();
    }

    /**
     * Departments visible in center/teacher/student flows.
     */
    public function scopeVisibleForSelection($query)
    {
        return $query
            ->where('status', 1)
            ->where(function ($query) {
                $query->where('years', '>', self::MIN_VISIBLE_YEARS)
                    ->orWhereIn('system_id', [2, 3]);
            })
            ->where('local_score', '>=', self::MIN_VISIBLE_LOCAL_SCORE);
    }
    
    public function headings(): array
    {
        return [
            'ID',
            'سیستەم',
            'پارێزگا',
            'زانکۆ',
            'کۆلێژ',
            'ناوی بەش',
            'ناوی بەش (ئینگلیزی)',
            'ن. ناوەندی',
            'ن. دەرەوە',
            'جۆر',
            'ڕەگەز',
            'Latitude',
            'Longitude',
            'وەسف',
            'دۆخ (1=چاڵاک, 0=ناچاڵاک)'
        ];
    }
    
    public function map($department): array
    {
        return [
            $department->id,
            $department->system->name,
            $department->province->name,
            $department->university->name,
            $department->college->name,
            $department->name,
            $department->name_en,
            $department->local_score,
            $department->external_score,
            $department->type,
            $department->sex,
            $department->lat,
            $department->lng,
            $department->description,
            $department->status
        ];
    }
}
