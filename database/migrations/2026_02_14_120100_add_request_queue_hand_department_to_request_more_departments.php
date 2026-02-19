<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('request_more_departments')) {
            return;
        }

        Schema::table('request_more_departments', function (Blueprint $table) {
            if (!Schema::hasColumn('request_more_departments', 'request_queue_hand_department')) {
                $table->boolean('request_queue_hand_department')
                    ->default(false)
                    ->after('request_gis');
            }
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('request_more_departments')) {
            return;
        }

        Schema::table('request_more_departments', function (Blueprint $table) {
            if (Schema::hasColumn('request_more_departments', 'request_queue_hand_department')) {
                $table->dropColumn('request_queue_hand_department');
            }
        });
    }
};
