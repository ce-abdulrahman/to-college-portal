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
        Schema::table('request_more_departments', function (Blueprint $table) {
            // Add support for Teachers and Centers
            $table->unsignedBigInteger('teacher_id')->nullable()->after('student_id');
            $table->unsignedBigInteger('center_id')->nullable()->after('teacher_id');
            $table->enum('user_type', ['student', 'teacher', 'center'])->default('student')->after('center_id');
            
            // Add foreign keys
            $table->foreign('teacher_id')->references('id')->on('teachers')->onDelete('cascade');
            $table->foreign('center_id')->references('id')->on('centers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('request_more_departments', function (Blueprint $table) {
            $table->dropForeign(['teacher_id']);
            $table->dropForeign(['center_id']);
            $table->dropColumn(['teacher_id', 'center_id', 'user_type']);
        });
    }
};
