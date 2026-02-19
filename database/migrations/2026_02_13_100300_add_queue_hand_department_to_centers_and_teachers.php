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
                if (!Schema::hasColumn('centers', 'queue_hand_department')) {
                    $table->boolean('queue_hand_department')->default(0)->after('all_departments');
                }
            });
        }

        if (Schema::hasTable('teachers')) {
            Schema::table('teachers', function (Blueprint $table) {
                if (!Schema::hasColumn('teachers', 'queue_hand_department')) {
                    $table->boolean('queue_hand_department')->default(0)->after('all_departments');
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
                if (Schema::hasColumn('centers', 'queue_hand_department')) {
                    $table->dropColumn('queue_hand_department');
                }
            });
        }

        if (Schema::hasTable('teachers')) {
            Schema::table('teachers', function (Blueprint $table) {
                if (Schema::hasColumn('teachers', 'queue_hand_department')) {
                    $table->dropColumn('queue_hand_department');
                }
            });
        }
    }
};

