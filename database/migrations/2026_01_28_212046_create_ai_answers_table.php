<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ai_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->foreignId('question_id')->constrained('ai_questions')->onDelete('cascade');
            $table->text('answer'); // دەتوانێت بێت JSON یان string
            $table->float('score', 5, 2)->default(0); // نمرەی وەڵامەکە
            $table->timestamps();
            
            $table->unique(['student_id', 'question_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_answers');
    }
};