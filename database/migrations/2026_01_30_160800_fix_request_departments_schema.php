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
        // Ensure the table exists
        if (Schema::hasTable('request_more_departments')) {
            Schema::table('request_more_departments', function (Blueprint $table) {
                
                // Add teacher_id if missing
                if (!Schema::hasColumn('request_more_departments', 'teacher_id')) {
                    $table->unsignedBigInteger('teacher_id')->nullable()->after('student_id');
                    if (Schema::hasTable('teachers')) {
                         $table->foreign('teacher_id')->references('id')->on('teachers')->onDelete('cascade');
                    }
                }

                // Add center_id if missing
                if (!Schema::hasColumn('request_more_departments', 'center_id')) {
                    $table->unsignedBigInteger('center_id')->nullable()->after('teacher_id');
                    if (Schema::hasTable('centers')) {
                        $table->foreign('center_id')->references('id')->on('centers')->onDelete('cascade');
                    }
                }

                // Add user_type if missing
                if (!Schema::hasColumn('request_more_departments', 'user_type')) {
                    $table->enum('user_type', ['student', 'teacher', 'center'])->default('student')->after('center_id');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Optional: drop columns
    }
};
