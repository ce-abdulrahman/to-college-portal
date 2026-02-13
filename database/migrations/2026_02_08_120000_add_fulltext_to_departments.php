<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('departments')) {
            return;
        }

        $driver = Schema::getConnection()->getDriverName();
        if (!in_array($driver, ['mysql', 'mariadb'], true)) {
            return;
        }

        Schema::table('departments', function (Blueprint $table) {
            $table->fullText(['name', 'name_en'], 'departments_name_fulltext');
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('departments')) {
            return;
        }

        $driver = Schema::getConnection()->getDriverName();
        if (!in_array($driver, ['mysql', 'mariadb'], true)) {
            return;
        }

        Schema::table('departments', function (Blueprint $table) {
            $table->dropFullText('departments_name_fulltext');
        });
    }
};
