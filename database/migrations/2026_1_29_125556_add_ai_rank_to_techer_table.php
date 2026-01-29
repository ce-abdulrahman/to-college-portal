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
        Schema::table('teachers', function (Blueprint $table) {
            $table->boolean('ai_rank')->default(0)->after('status');
            $table->boolean('gis')->default(0)->after('ai_rank');
            $table->boolean('all_departments')->default(0)->after('gis');
        });
    }

    /** 
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('teachers', function (Blueprint $table) {
            $table->dropColumn('ai_rank');
            $table->dropColumn('gis');
            $table->dropColumn('all_departments');
        });
    }
};
