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
        if (Schema::hasTable('centers')) {
            Schema::table('centers', function (Blueprint $table) {
                if (!Schema::hasColumn('centers', 'limit_teacher')) {
                    $table->unsignedInteger('limit_teacher')->default(0)->after('all_departments');
                }
                if (!Schema::hasColumn('centers', 'limit_student')) {
                    $table->unsignedInteger('limit_student')->default(0)->after('limit_teacher');
                }
            });
        }

        if (Schema::hasTable('teachers')) {
            Schema::table('teachers', function (Blueprint $table) {
                if (!Schema::hasColumn('teachers', 'limit_student')) {
                    $table->unsignedInteger('limit_student')->default(0)->after('all_departments');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('centers')) {
            Schema::table('centers', function (Blueprint $table) {
                if (Schema::hasColumn('centers', 'limit_teacher')) {
                    $table->dropColumn('limit_teacher');
                }
                if (Schema::hasColumn('centers', 'limit_student')) {
                    $table->dropColumn('limit_student');
                }
            });
        }

        if (Schema::hasTable('teachers')) {
            Schema::table('teachers', function (Blueprint $table) {
                if (Schema::hasColumn('teachers', 'limit_student')) {
                    $table->dropColumn('limit_student');
                }
            });
        }
    }
};
