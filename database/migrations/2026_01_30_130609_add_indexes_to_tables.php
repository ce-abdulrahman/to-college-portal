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
        // 1. Departments Table
        if (Schema::hasTable('departments')) {
            Schema::table('departments', function (Blueprint $table) {
                // We use try-catch to safely ignore "Already exists" errors if we can't check easily
                try {
                    $table->index('local_score', 'deps_score_idx');
                } catch (\Exception $e) {}

                try {
                    $table->index('name', 'deps_name_idx');
                } catch (\Exception $e) {}

                try {
                    $table->index(['type', 'sex', 'local_score'], 'deps_filter_idx');
                } catch (\Exception $e) {}
                
                try {
                    $table->index('name_en', 'deps_name_en_idx');
                } catch (\Exception $e) {}
            });
        }

        // 2. Students Table
        if (Schema::hasTable('students')) {
            Schema::table('students', function (Blueprint $table) {
                try {
                    $table->index('code', 'st_code_idx');
                } catch (\Exception $e) {}
            });
        }
        
        // 3. Universities & Colleges
        if (Schema::hasTable('universities')) {
             Schema::table('universities', function (Blueprint $table) {
                try {
                    $table->index('name', 'uni_name_idx');
                } catch (\Exception $e) {}
             });
        }

         if (Schema::hasTable('colleges')) {
             Schema::table('colleges', function (Blueprint $table) {
                try {
                    $table->index('name', 'coll_name_idx');
                } catch (\Exception $e) {}
             });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('departments')) {
            Schema::table('departments', function (Blueprint $table) {
                try { $table->dropIndex('deps_score_idx'); } catch (\Exception $e) {}
                try { $table->dropIndex('deps_name_idx'); } catch (\Exception $e) {}
                try { $table->dropIndex('deps_filter_idx'); } catch (\Exception $e) {}
                try { $table->dropIndex('deps_name_en_idx'); } catch (\Exception $e) {}
            });
        }

        if (Schema::hasTable('students')) {
            Schema::table('students', function (Blueprint $table) {
                try { $table->dropIndex('st_code_idx'); } catch (\Exception $e) {}
            });
        }
        
        if (Schema::hasTable('universities')) {
             Schema::table('universities', function (Blueprint $table) {
                try { $table->dropIndex('uni_name_idx'); } catch (\Exception $e) {}
             });
        }
        
        if (Schema::hasTable('colleges')) {
             Schema::table('colleges', function (Blueprint $table) {
                try { $table->dropIndex('coll_name_idx'); } catch (\Exception $e) {}
             });
        }
    }
};
