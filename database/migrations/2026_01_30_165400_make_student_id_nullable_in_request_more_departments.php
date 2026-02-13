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
        Schema::dropIfExists('request_more_departments');
        
        Schema::create('request_more_departments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id')->nullable();
            $table->unsignedBigInteger('teacher_id')->nullable();
            $table->unsignedBigInteger('center_id')->nullable();
            $table->enum('user_type', ['student', 'teacher', 'center'])->default('student');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->boolean('request_all_departments')->default(false);
            $table->boolean('request_ai_rank')->default(false);
            $table->boolean('request_gis')->default(false);
            $table->text('reason')->nullable();
            $table->string('receipt_image')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('admin_notes')->nullable();
            $table->foreignId('admin_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
            
            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
            $table->foreign('teacher_id')->references('id')->on('teachers')->onDelete('cascade');
            $table->foreign('center_id')->references('id')->on('centers')->onDelete('cascade');
            $table->index(['status', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('request_more_departments', function (Blueprint $table) {
            $table->unsignedBigInteger('student_id')->nullable(false)->change();
        });
    }
};
