<?php

namespace App\Imports;

use App\Models\Department;
use App\Models\System;
use App\Models\Province;
use App\Models\University;
use App\Models\College;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class DepartmentsImport implements ToModel, WithHeadingRow, WithValidation
{
    private $updateExisting;
    
    public function __construct($updateExisting = false)
    {
        $this->updateExisting = $updateExisting;
    }
    
    public function model(array $row)
    {
        // دۆزینەوەی پەیوەندیدارەکان بەپێی ناو
        $system = System::where('name', $row['سیستەم'])->first();
        $province = Province::where('name', $row['پارێزگا'])->first();
        $university = University::where('name', $row['زانکۆ'])->first();
        $college = College::where('name', $row['کۆلێژ'])->first();
        
        if (!$system || !$province || !$university || !$college) {
            throw new \Exception("پەیوەندیەکانی بەشەکە نەدۆزرایەوە. تکایە دڵنیابە کە هەموو پەیوەندیەکان بوونیان هەیە.");
        }
        
        $data = [
            'system_id' => $system->id,
            'province_id' => $province->id,
            'university_id' => $university->id,
            'college_id' => $college->id,
            'name' => $row['ناوی بەش'],
            'name_en' => $row['ناوی بەش (ئینگلیزی)'] ?? $row['nawey besh (english)'],
            'local_score' => $row['ن. ناوەندی'] ?? null,
            'external_score' => $row['ن. دەرەوە'] ?? null,
            'type' => $row['جۆر'] ?? 'زانستی',
            'sex' => $row['ڕەگەز'] ?? 'نێر',
            'lat' => $row['latitude'] ?? null,
            'lng' => $row['longitude'] ?? null,
            'description' => $row['وەسف'] ?? null,
            'status' => $row['دۆخ (1=چاڵاک, 0=ناچاڵاک)'] ?? 1
        ];
        
        if ($this->updateExisting && isset($row['id'])) {
            return Department::updateOrCreate(['id' => $row['id']], $data);
        }
        
        return new Department($data);
    }
    
    public function rules(): array
    {
        return [
            'ناوی بەش' => 'required|string',
            'ناوی بەش (ئینگلیزی)' => 'required|string',
            'سیستەم' => 'required|exists:systems,name',
            'پارێزگا' => 'required|exists:provinces,name',
            'زانکۆ' => 'required|exists:universities,name',
            'کۆلێژ' => 'required|exists:colleges,name',
        ];
    }
}