<?php
// [file name]: DepartmentsTemplateExport.php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;

class DepartmentsTemplateExport implements FromArray, WithHeadings, WithTitle, WithStrictNullComparison
{
    public function array(): array
    {
        // نموونەی داتای بەتاڵ
        return [
            [
                '', // ID (بەتاڵ بێت)
                'زانکۆلاین', // سیستەم
                'هەولێر', // پارێزگا
                'زانکۆی سەڵاحەدین', // زانکۆ
                'کۆلێژی زانست', // کۆلێژ
                'کۆمپیوتەر', // ناوی بەش
                'Computer Science', // ناوی بەش (ئینگلیزی)
                '85.5', // ن. ناوەندی
                '82.3', // ن. دەرەوە
                'زانستی', // جۆر
                'نێر', // ڕەگەز
                '36.1911', // Latitude
                '44.0092', // Longitude
                'بەشی کۆمپیوتەر بۆ خوێندنی باڵا', // وەسف
                '1' // دۆخ
            ],
            [
                '',
                'پاراڵیل',
                'دهۆک',
                'زانکۆی دهۆک',
                'کۆلێژی ئەندازیاری',
                'ئەندازیاری شارستانی',
                'Civil Engineering',
                '80.0',
                '78.5',
                'زانستی',
                'مێ',
                '36.8680',
                '43.0000',
                'بەشی ئەندازیاری شارستانی',
                '1'
            ]
        ];
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
    
    public function title(): string
    {
        return 'Departments Template';
    }
}