<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ai_questions', function (Blueprint $table) {
            $table->id();
            $table->string('question_ku');
            $table->string('question_en')->nullable();
            $table->string('category'); // personality, interest, location, priority
            $table->json('options')->nullable(); // بۆ پرسیارەکانی وەڵامی جیاجیا
            $table->integer('weight')->default(1); // قورسایی پرسیارەکە
            $table->json('department_weights')->nullable(); // پەیوەندی بە بەشەکانەوە
            $table->integer('order')->default(0);
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_questions');
    }
};