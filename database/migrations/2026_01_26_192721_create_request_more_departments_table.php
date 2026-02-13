<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('request_more_departments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            
            // Remove ALL ->after() methods completely
            $table->unsignedBigInteger('teacher_id')->nullable();
            $table->unsignedBigInteger('center_id')->nullable();
            $table->enum('user_type', ['student', 'teacher', 'center'])->default('student');
            
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            
            $table->boolean('request_all_departments')->default(false)->comment('مۆڵەتی ٥٠ بەش');
            $table->boolean('request_ai_rank')->default(false)->comment('سیستەمی AI');
            $table->boolean('request_gis')->default(false)->comment('سیستەمی نەخشە (GIS)');
            
            $table->text('reason')->nullable();
            $table->string('receipt_image')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('admin_notes')->nullable();
            $table->foreignId('admin_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
        });
        
        // Add foreign keys and indexes in a separate statement
        Schema::table('request_more_departments', function (Blueprint $table) {
            $table->foreign('teacher_id')->references('id')->on('teachers')->onDelete('cascade');
            $table->foreign('center_id')->references('id')->on('centers')->onDelete('cascade');
            $table->index(['status', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('request_more_departments');
    }
};
