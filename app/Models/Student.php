<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'mark',
        'province',
        'type',
        'gender',
        'year',
        'referral_code',
        'status',
        'mbti_type',
    ];

    protected $casts = [
        'mark' => 'integer',
        'year' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function resultDeps()
    {
        return $this->hasMany(ResultDep::class, 'student_id');
    }

    public function mbtiAnswers()
    {
        return $this->hasMany(MbtiAnswer::class);
    }

    // ئەمە بۆ MBTI
    public function calculateMbtiResult()
    {
        $answers = $this->mbtiAnswers()->with('question')->get();
        
        if ($answers->isEmpty()) {
            return null;
        }
        
        $scores = [
            'E' => 0, 'I' => 0,
            'S' => 0, 'N' => 0,
            'T' => 0, 'F' => 0,
            'J' => 0, 'P' => 0,
        ];
        
        foreach ($answers as $answer) {
            $side = $answer->question->side;
            $scores[$side] += $answer->score;
        }
        
        $mbtiType = '';
        $mbtiType .= ($scores['E'] > $scores['I']) ? 'E' : 'I';
        $mbtiType .= ($scores['S'] > $scores['N']) ? 'S' : 'N';
        $mbtiType .= ($scores['T'] > $scores['F']) ? 'T' : 'F';
        $mbtiType .= ($scores['J'] > $scores['P']) ? 'J' : 'P';
        
        return $mbtiType;
    }
    
    public function getMbtiFullNameAttribute()
    {
        $types = [
            'ISTJ' => 'Introverted Sensor Thinker Judger',
            'ISFJ' => 'Introverted Sensor Feeler Judger',
            'INFJ' => 'Introverted Intuitive Feeler Judger',
            'INTJ' => 'Introverted Intuitive Thinker Judger',
            'ISTP' => 'Introverted Sensor Thinker Perceiver',
            'ISFP' => 'Introverted Sensor Feeler Perceiver',
            'INFP' => 'Introverted Intuitive Feeler Perceiver',
            'INTP' => 'Introverted Intuitive Thinker Perceiver',
            'ESTP' => 'Extraverted Sensor Thinker Perceiver',
            'ESFP' => 'Extraverted Sensor Feeler Perceiver',
            'ENFP' => 'Extraverted Intuitive Feeler Perceiver',
            'ENTP' => 'Extraverted Intuitive Thinker Perceiver',
            'ESTJ' => 'Extraverted Sensor Thinker Judger',
            'ESFJ' => 'Extraverted Sensor Feeler Judger',
            'ENFJ' => 'Extraverted Intuitive Feeler Judger',
            'ENTJ' => 'Extraverted Intuitive Thinker Judger',
        ];
        
        return $types[$this->mbti_type] ?? 'دیاری نەکراو';
    }
    
    public function getMbtiKurdishDescriptionAttribute()
    {
        $descriptions = [
            'ISTJ' => 'کەسێکی ئاسوودەخواز و ڕێکخراو',
            'ISFJ' => 'کەسێکی پارێزەر و پشتگیری',
            'INFJ' => 'کەسێکی ڕاوێژکار و داهێنەر',
            'INTJ' => 'کەسێکی ستراتیژیست و بیرکار',
            'ISTP' => 'کەسێکی چارەسەرکەر و پڕاکتیکی',
            'ISFP' => 'کەسێکی هونەرمەند و هەستیار',
            'INFP' => 'کەسێکی ئایدیالیست و میهرەبان',
            'INTP' => 'کەسێکی بیرکار و نەریتی',
            'ESTP' => 'کەسێکی بەهرەمەند و ئەنەرجەتیک',
            'ESFP' => 'کەسێکی ڕابەر و سۆزی',
            'ENFP' => 'کەسێکی پیشاندەر و داهێنەر',
            'ENTP' => 'کەسێکی داهێنەر و بیرکار',
            'ESTJ' => 'کەسێکی بەڕێوەبەر و ڕێکخراو',
            'ESFJ' => 'کەسێکی بەخێوکەر و کۆمەڵایەتی',
            'ENFJ' => 'کەسێکی مامۆستا و هاوسۆز',
            'ENTJ' => 'کەسێکی فەرماندە و ستراتیژیست',
        ];
        
        return $descriptions[$this->mbti_type] ?? 'هیچ زانیاریەک بوونی نییە';
    }
    
    public function hasCompletedMbtiTest()
    {
        return !empty($this->mbti_type) || $this->mbtiAnswers()->exists();
    }
}