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
    
    // Cache arrays for fast lookup
    private $systems;
    private $provinces;
    private $universities;
    private $colleges;
    
    public function __construct($updateExisting = false)
    {
        $this->updateExisting = $updateExisting;
        
        // Load all relationships into memory once (Name => ID)
        $this->systems = System::pluck('id', 'name')->toArray();
        $this->provinces = Province::pluck('id', 'name')->toArray();
        $this->universities = University::pluck('id', 'name')->toArray();
        $this->colleges = College::pluck('id', 'name')->toArray();
    }
    
    public function model(array $row)
    {
        // 1. Memory Lookups (O(1) complexity) instead of DB Queries
        $systemId = $this->systems[$row['سیستەم']] ?? null;
        $provinceId = $this->provinces[$row['پارێزگا']] ?? null;
        $universityId = $this->universities[$row['زانکۆ']] ?? null;
        $collegeId = $this->colleges[$row['کۆلێژ']] ?? null;
        
        if (!$systemId || !$provinceId || !$universityId || !$collegeId) {
            // Detailed error to help user know which relation is missing
            $missing = [];
            if (!$systemId) $missing[] = "سیستەم (" . $row['سیستەم'] . ")";
            if (!$provinceId) $missing[] = "پارێزگا (" . $row['پارێزگا'] . ")";
            if (!$universityId) $missing[] = "زانکۆ (" . $row['زانکۆ'] . ")";
            if (!$collegeId) $missing[] = "کۆلێژ (" . $row['کۆلێژ'] . ")";
            
            throw new \Exception("پەیوەندیەکانی بەشەکە نەدۆزرایەوە: " . implode(', ', $missing));
        }
        
        $data = [
            'system_id' => $systemId,
            'province_id' => $provinceId,
            'university_id' => $universityId,
            'college_id' => $collegeId,
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