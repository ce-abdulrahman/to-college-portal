<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ai_rankings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->foreignId('department_id')->constrained('departments')->onDelete('cascade');
            $table->integer('rank')->nullable();
            $table->integer('result_rank')->nullable();
            $table->timestamps();
            
            $table->unique(['student_id', 'department_id']);
            $table->index(['student_id', 'rank']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_rankings');
    }
};
