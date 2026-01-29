<?php

namespace App\Exports;

use App\Models\Department;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class DepartmentsExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Department::with(['system', 'province', 'university', 'college'])->get();
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