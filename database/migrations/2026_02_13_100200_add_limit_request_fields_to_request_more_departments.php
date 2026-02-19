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
        if (!Schema::hasTable('request_more_departments')) {
            return;
        }

        Schema::table('request_more_departments', function (Blueprint $table) {
            if (!Schema::hasColumn('request_more_departments', 'request_limit_teacher')) {
                $table->unsignedInteger('request_limit_teacher')->default(0)->after('request_gis');
            }
            if (!Schema::hasColumn('request_more_departments', 'request_limit_student')) {
                $table->unsignedInteger('request_limit_student')->default(0)->after('request_limit_teacher');
            }
            if (!Schema::hasColumn('request_more_departments', 'approved_limit_teacher')) {
                $table->unsignedInteger('approved_limit_teacher')->default(0)->after('request_limit_student');
            }
            if (!Schema::hasColumn('request_more_departments', 'approved_limit_student')) {
                $table->unsignedInteger('approved_limit_student')->default(0)->after('approved_limit_teacher');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasTable('request_more_departments')) {
            return;
        }

        Schema::table('request_more_departments', function (Blueprint $table) {
            if (Schema::hasColumn('request_more_departments', 'request_limit_teacher')) {
                $table->dropColumn('request_limit_teacher');
            }
            if (Schema::hasColumn('request_more_departments', 'request_limit_student')) {
                $table->dropColumn('request_limit_student');
            }
            if (Schema::hasColumn('request_more_departments', 'approved_limit_teacher')) {
                $table->dropColumn('approved_limit_teacher');
            }
            if (Schema::hasColumn('request_more_departments', 'approved_limit_student')) {
                $table->dropColumn('approved_limit_student');
            }
        });
    }
};
