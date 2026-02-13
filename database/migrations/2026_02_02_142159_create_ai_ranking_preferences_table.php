<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ai_ranking_preferences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');

            // تایبەتمەندیەکان
            $table->boolean('consider_personality')->default(true);
            $table->boolean('include_specific_questions')->default(true);
            $table->boolean('prefer_nearby_departments')->default(true);
            $table->boolean('use_mark_bonus')->default(true);
            $table->boolean('mark_bonus_enabled')->default(true);

            // فیلتەرەکان (JSON)
            $table->json('preferred_systems')->nullable(); // [1,2,3]
            $table->json('gender_filter')->nullable(); // ['نێر','مێ']
            $table->json('field_type_filter')->nullable(); // ['زانستی','وێژەیی']
            $table->string('province_filter')->nullable();

            $table->timestamps();

            // یەکتا: ہەر قوتابی تەنها یەک preference ھاتن لە یەک
            $table->unique('student_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ai_ranking_preferences');
    }
};
