<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('mbti_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->foreignId('question_id')->constrained('mbti_questions')->onDelete('cascade');
            $table->integer('score')->default(0); // نمرە لە 1-10
            $table->timestamps();

            $table->unique(['user_id', 'student_id', 'question_id']); // تەنها یەک وەڵام بۆ هەر پرسیارێک
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mbti_answers');
    }
};
