<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MbtiQuestion;

class MbtiQuestionsSeeder extends Seeder
{
    public function run()
    {
        $questions = [
            // ==================== EI DIMENSION ====================
            // E - Extraversion (9 questions)
            [
                'dimension' => 'EI',
                'side' => 'E',
                'question_ku' => 'حەزم لە کاری جۆراوجۆر دەکەم',
                'order' => 1,
            ],
            [
                'dimension' => 'EI',
                'side' => 'E',
                'question_ku' => 'بە توانام لە پێشوازی کردنی خەڵکدا',
                'order' => 2,
            ],
            [
                'dimension' => 'EI',
                'side' => 'E',
                'question_ku' => 'حەزم لە کاری بە پێیه',
                'order' => 3,
            ],
            [
                'dimension' => 'EI',
                'side' => 'E',
                'question_ku' => 'گرنگی بە ڕای بەرامبەر دەدەم بۆ کارەکەم',
                'order' => 4,
            ],
            [
                'dimension' => 'EI',
                'side' => 'E',
                'question_ku' => 'حەزم لە قسەکردنی ناو تەلەفونە و لە ناردنی نامە',
                'order' => 5,
            ],
            [
                'dimension' => 'EI',
                'side' => 'E',
                'question_ku' => 'هەندێک جار بە یەک بیرکردنەوە کار دەکەم',
                'order' => 6,
            ],
            [
                'dimension' => 'EI',
                'side' => 'E',
                'question_ku' => 'حەز دەکەم لە کاتی شیکردنەوەدا خەڵک لەگەڵم بن',
                'order' => 7,
            ],
            [
                'dimension' => 'EI',
                'side' => 'E',
                'question_ku' => 'قسەکردنم پی باشترە و لە نوسین',
                'order' => 8,
            ],
            [
                'dimension' => 'EI',
                'side' => 'E',
                'question_ku' => 'حەز دەکەم کاری نوێ فیرع بێت لە نەجامی گفتوگۆ لەگەڵ خەڵکیدا',
                'order' => 9,
            ],

            // I - Introversion (9 questions)
            [
                'dimension' => 'EI',
                'side' => 'I',
                'question_ku' => 'حەزم لە ئەمڕۆبوونەوەی تەمەرکیزم زیاترە',
                'order' => 10,
            ],
            [
                'dimension' => 'EI',
                'side' => 'I',
                'question_ku' => 'گرەفتەم هەمیشە لە ناسینەوەی دەنگ و دەودای ناو مکاندا',
                'order' => 11,
            ],
            [
                'dimension' => 'EI',
                'side' => 'I',
                'question_ku' => 'ناتوانم بۆ ماوەیەکی دور و درێژ لەم سەرۆژە کار بکەم بە یەک بچران',
                'order' => 12,
            ],
            [
                'dimension' => 'EI',
                'side' => 'I',
                'question_ku' => 'زۆر گرنگی دەدەم بە نەجام لە کارەکەمدا',
                'order' => 13,
            ],
            [
                'dimension' => 'EI',
                'side' => 'I',
                'question_ku' => 'ڕەقم لە بچراندنی بیرکردنەوەکەمە بەهۆی تەلەفونەوە',
                'order' => 14,
            ],
            [
                'dimension' => 'EI',
                'side' => 'I',
                'question_ku' => 'بیر دەکەمەوە پێش ئەوەی کار بکەم و هەندێک جار بیر دەکەمەوە کارەکەش دەکەم',
                'order' => 15,
            ],
            [
                'dimension' => 'EI',
                'side' => 'I',
                'question_ku' => 'بە تەنها دەتوانم کار بکەم نەک قەناعەتم یەتی هەبور',
                'order' => 16,
            ],
            [
                'dimension' => 'EI',
                'side' => 'I',
                'question_ku' => 'حەزم لە بەئەندامیبوونە لە ڕاپرسیەکی نوسینەوە',
                'order' => 17,
            ],
            [
                'dimension' => 'EI',
                'side' => 'I',
                'question_ku' => 'حەزم لە فیریوونە بە خوێندنەوە و لە قسەکردن',
                'order' => 18,
            ],

            // ==================== SN DIMENSION ====================
            // S - Sensing (9 questions)
            [
                'dimension' => 'SN',
                'side' => 'S',
                'question_ku' => 'هەست بە شیکرە پێشهاتەکانی ناو ڕوداوەکان دەکەم',
                'order' => 1,
            ],
            [
                'dimension' => 'SN',
                'side' => 'S',
                'question_ku' => 'تەمەرکیزم لەسەر کاری ڕاستەقینەیە',
                'order' => 2,
            ],
            [
                'dimension' => 'SN',
                'side' => 'S',
                'question_ku' => 'حەزم لە ڕوونکردنەوەی ڕووسن و شیکرایە بۆ شیکردنەوەی کارەکانم',
                'order' => 3,
            ],
            [
                'dimension' => 'SN',
                'side' => 'S',
                'question_ku' => 'حەزم لە جنبەچیکردنی شوێنەهێنەیە کە فیزی بووم',
                'order' => 4,
            ],
            [
                'dimension' => 'SN',
                'side' => 'S',
                'question_ku' => 'بەردەوامیم هەیە لە کارکردن لەسەر ڕاستیەکان هەرچەندە کاتی زۆریش بگریت',
                'order' => 5,
            ],
            [
                'dimension' => 'SN',
                'side' => 'S',
                'question_ku' => 'هەنگاو بە هەنگاو دەڕۆم بە کوتایی',
                'order' => 6,
            ],
            [
                'dimension' => 'SN',
                'side' => 'S',
                'question_ku' => 'بڕوام بە نیگاهام نییە و کاریش بە نیگاهام ناکەم، بەڵکو بڕوام وایە کە دەبینم و دەیستم',
                'order' => 7,
            ],
            [
                'dimension' => 'SN',
                'side' => 'S',
                'question_ku' => 'زۆر مەیلەهە ڕاستیەکان بزادم',
                'order' => 8,
            ],
            [
                'dimension' => 'SN',
                'side' => 'S',
                'question_ku' => 'لە کارە وردەکاندا بەخوێنمام',
                'order' => 9,
            ],

            // N - Intuition (9 questions)
            [
                'dimension' => 'SN',
                'side' => 'N',
                'question_ku' => 'هەست بە کۆسەکانی ڕوونگا و توانا نوێکان دەکەم',
                'order' => 10,
            ],
            [
                'dimension' => 'SN',
                'side' => 'N',
                'question_ku' => 'تەمەرکیزم لەسەر پێهەڵگەیاندنی شتەکان دەکەم',
                'order' => 11,
            ],
            [
                'dimension' => 'SN',
                'side' => 'N',
                'question_ku' => 'ڕەقم لە دووبارەکردنەوەی شتەکانە',
                'order' => 12,
            ],
            [
                'dimension' => 'SN',
                'side' => 'N',
                'question_ku' => 'حەزم لە فێربوونی مەهارەت و کاری نوێیە',
                'order' => 13,
            ],
            [
                'dimension' => 'SN',
                'side' => 'N',
                'question_ku' => 'بە توانا و خێرایی زۆرەوە کار دەکەم',
                'order' => 14,
            ],
            [
                'dimension' => 'SN',
                'side' => 'N',
                'question_ku' => 'زۆر بەخێرایی دەڕۆم بۆ کوتایی',
                'order' => 15,
            ],
            [
                'dimension' => 'SN',
                'side' => 'N',
                'question_ku' => 'کار بۆ هێنانەدی هەست و حەزەکان و بیرکردنەوەکانم دەکەم',
                'order' => 16,
            ],
            [
                'dimension' => 'SN',
                'side' => 'N',
                'question_ku' => 'هەندێک جار ڕاستیەکان بەهەڵمەگرم',
                'order' => 17,
            ],
            [
                'dimension' => 'SN',
                'side' => 'N',
                'question_ku' => 'ڕەقم لەوەیە کات بە شتە وردەوە بکوژم',
                'order' => 18,
            ],

            // ==================== TF DIMENSION ====================
            // F - Feeling (9 questions)
            [
                'dimension' => 'TF',
                'side' => 'F',
                'question_ku' => 'حەزم لە گونجان و پێکه‌وه‌ژیانی ده‌م و هەولی بەدەستهێنانی دەم',
                'order' => 1,
            ],
            [
                'dimension' => 'TF',
                'side' => 'F',
                'question_ku' => 'بەم بیر و ڕا و هەڵکەوتەکانی خەڵکەوە دەچم',
                'order' => 2,
            ],
            [
                'dimension' => 'TF',
                'side' => 'F',
                'question_ku' => 'باش دەنوێنم کەم سی شیاو هەلیزترم',
                'order' => 3,
            ],
            [
                'dimension' => 'TF',
                'side' => 'F',
                'question_ku' => 'هەندێکجار پێویستم بە دەستخۆشی هەیە',
                'order' => 4,
            ],
            [
                'dimension' => 'TF',
                'side' => 'F',
                'question_ku' => 'لە کاتی بەرەنگاربووندا خۆم دەخەمە جێی کەسە بەرامبەرە باشەکان بەرەنگار دەکەم',
                'order' => 5,
            ],
            [
                'dimension' => 'TF',
                'side' => 'F',
                'question_ku' => 'حەزم لەوەیە لەگەڵ خەڵکی دڵخۆشدا بم',
                'order' => 6,
            ],
            [
                'dimension' => 'TF',
                'side' => 'F',
                'question_ku' => 'گرنگی بە خەڵکی خاوەنکار و فیکرد دەدەم',
                'order' => 7,
            ],
            [
                'dimension' => 'TF',
                'side' => 'F',
                'question_ku' => 'بەخشینی ماددی و سوزداریم هەیە',
                'order' => 8,
            ],
            [
                'dimension' => 'TF',
                'side' => 'F',
                'question_ku' => 'لە کاتی زۆرکردنی بەرامبەردا زۆر گوێبیار دەم و بیری لە دەمکەوە',
                'order' => 9,
            ],

            // T - Thinking (9 questions)
            [
                'dimension' => 'TF',
                'side' => 'T',
                'question_ku' => 'بەتوانام لە دانانی شتەکان لە شوێنی گونجاو خۆیان',
                'order' => 10,
            ],
            [
                'dimension' => 'TF',
                'side' => 'T',
                'question_ku' => 'وەزع دانەوەم بۆ فیکری خەڵکی زیاتر وکەلە هەست و سوزیان',
                'order' => 11,
            ],
            [
                'dimension' => 'TF',
                'side' => 'T',
                'question_ku' => 'چاوەڕوانی نەجامی مەرجی دەکەم لە هەلسفتگاندنەکاندا',
                'order' => 12,
            ],
            [
                'dimension' => 'TF',
                'side' => 'T',
                'question_ku' => 'پێویستم بەوە هەیە کە بە دادپەروەرانە مامەڵە لەگەڵدا بکرێت',
                'order' => 13,
            ],
            [
                'dimension' => 'TF',
                'side' => 'T',
                'question_ku' => 'بەلا یە نەما دەچم کە خوگر و ڕاستەقینە بم',
                'order' => 14,
            ],
            [
                'dimension' => 'TF',
                'side' => 'T',
                'question_ku' => 'توانای دەمەقاڵی و سەرزنشتکردنم هەیە نەگەر پێویست بکات',
                'order' => 15,
            ],
            [
                'dimension' => 'TF',
                'side' => 'T',
                'question_ku' => 'لەوانەیە هەستی خەڵکی بەرەنگاریکەم بە یەک نموونە بزانم',
                'order' => 16,
            ],
            [
                'dimension' => 'TF',
                'side' => 'T',
                'question_ku' => 'توانای شیرکردنەوەی کێشەکەم هەیە',
                'order' => 17,
            ],
            [
                'dimension' => 'TF',
                'side' => 'T',
                'question_ku' => 'بە یەک مەرجی هەست و سوزم دەگونجێنم',
                'order' => 18,
            ],

            // ==================== JP DIMENSION ====================
            // P - Perceiving (9 questions)
            [
                'dimension' => 'JP',
                'side' => 'P',
                'question_ku' => 'ڕێز لەوە دەگرم کە کارەکان کراوە بن بۆ ڕۆژی گوڕانکاران پاشەڕۆدا بێت',
                'order' => 1,
            ],
            [
                'dimension' => 'JP',
                'side' => 'P',
                'question_ku' => 'توانای گونجام هەیە لەگەڵ گوڕانکاری نوێگان',
                'order' => 2,
            ],
            [
                'dimension' => 'JP',
                'side' => 'P',
                'question_ku' => 'کێشەی بەرەنگاربوونم هەیە و هەست دەکەم زانیاری تەواو نەبێت بۆ چارەسەر',
                'order' => 3,
            ],
            [
                'dimension' => 'JP',
                'side' => 'P',
                'question_ku' => 'پلانی جۆراوجۆر دادەمەزرێم و دەست بە جنبەچیکردنیان دەکەم، بەلام فورسە لەسەرم هەموویان تەواو بکەم',
                'order' => 4,
            ],
            [
                'dimension' => 'JP',
                'side' => 'P',
                'question_ku' => 'کارە فورسەکان دوادەخەم',
                'order' => 5,
            ],
            [
                'dimension' => 'JP',
                'side' => 'P',
                'question_ku' => 'درەنگ دەکەمە نەجام',
                'order' => 6,
            ],
            [
                'dimension' => 'JP',
                'side' => 'P',
                'question_ku' => 'لیستی نوسراو بەکارناھێنم بۆ کارەکان کە پێویستە نەجامی بدەم',
                'order' => 7,
            ],
            [
                'dimension' => 'JP',
                'side' => 'P',
                'question_ku' => 'نەفەریم هەیە لەگەڵ مامەڵەی کار و کاتدا',
                'order' => 8,
            ],
            [
                'dimension' => 'JP',
                'side' => 'P',
                'question_ku' => 'دەمەوێت هەموو شتێک دەربارەی کارە نوێکان بزانم',
                'order' => 9,
            ],

            // J - Judging (9 questions)
            [
                'dimension' => 'JP',
                'side' => 'J',
                'question_ku' => 'بە جددی کار دەکەم کاتێک پلان دادەنێم و بە دوای پلانەکەشمەدا دەمێرم',
                'order' => 10,
            ],
            [
                'dimension' => 'JP',
                'side' => 'J',
                'question_ku' => 'حەزم لە نەجامدان و تەواوکردنی کارەکانە',
                'order' => 11,
            ],
            [
                'dimension' => 'JP',
                'side' => 'J',
                'question_ku' => 'زوو بەرەنگار دەبم',
                'order' => 12,
            ],
            [
                'dimension' => 'JP',
                'side' => 'J',
                'question_ku' => 'ڕەق لەوەم پلان و پرۆژەکانم پێ ببردرێت',
                'order' => 13,
            ],
            [
                'dimension' => 'JP',
                'side' => 'J',
                'question_ku' => 'هەست بە دڵخۆشی و ڕێزمانداری دەکەم کاتێک حوکم لەسەر کەس و حالەتەکان دەدەم',
                'order' => 14,
            ],
            [
                'dimension' => 'JP',
                'side' => 'J',
                'question_ku' => 'بۆ دەستپێکردنی کارەکان پێویستم بە زانینی بنەمای بنەڕەتیەکان هەیە',
                'order' => 15,
            ],
            [
                'dimension' => 'JP',
                'side' => 'J',
                'question_ku' => 'بەرنامەکان بە ڕۆژ و سەعات دیاری دەکەم',
                'order' => 16,
            ],
            [
                'dimension' => 'JP',
                'side' => 'J',
                'question_ku' => 'لیست بەکار دەهێنم بۆ چارەسەرەکان کە دەمەوێت نەجامیان بدەم',
                'order' => 17,
            ],
            [
                'dimension' => 'JP',
                'side' => 'J',
                'question_ku' => 'زۆر توندم لە مامەڵەکردن لەگەڵ کاتدا',
                'order' => 18,
            ],
        ];

        foreach ($questions as $questionData) {
            MbtiQuestion::updateOrCreate(
                [
                    'dimension' => $questionData['dimension'],
                    'side' => $questionData['side'],
                    'question_ku' => $questionData['question_ku'],
                ],
                $questionData
            );
        }

        $this->command->info(count($questions) . ' پرسیاری MBTI زیادکران.');
    }
}
