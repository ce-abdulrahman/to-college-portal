<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('result_deps', 'result_rank')) {
            Schema::table('result_deps', function (Blueprint $table) {
                $table->unsignedSmallInteger('result_rank')->nullable()->index()->after('rank');
            });
        }

        if (Schema::hasColumn('result_deps', 'status')) {
            Schema::table('result_deps', function (Blueprint $table) {
                $table->dropColumn('status');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('result_deps', 'result_rank')) {
            Schema::table('result_deps', function (Blueprint $table) {
                $table->dropColumn('result_rank');
            });
        }

        if (!Schema::hasColumn('result_deps', 'status')) {
            Schema::table('result_deps', function (Blueprint $table) {
                $table->boolean('status')->default(1);
            });
        }
    }
};
