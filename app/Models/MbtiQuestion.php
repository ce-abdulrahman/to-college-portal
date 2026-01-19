<?php

// app/Models/MbtiQuestion.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MbtiQuestion extends Model
{
    protected $fillable = [
        'dimension',
        'side',
        'question_ku',
        'question_en',
        'order'
    ];

    protected $casts = [
        'order' => 'integer',
    ];

    // پەیوەندی بە وەڵامەکانەوە
    public function answers()
    {
        return $this->hasMany(MbtiAnswer::class, 'question_id');
    }

    // Static method بۆ وەرگرتنی پرسیارەکان بە شێوەی ڕیزبەندکراو
    public static function getGroupedQuestions()
    {
        return self::orderBy('dimension')
                  ->orderBy('order')
                  ->get()
                  ->groupBy('dimension');
    }
}
