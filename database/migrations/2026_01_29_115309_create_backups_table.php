<?php
// Migrationەکەت گۆڕانکاری بکە
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('backups', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('file_path')->nullable(); // ئەمە گۆڕانکاریە!
            $table->string('database_type')->default('mysql');
            $table->string('source_db')->nullable();
            $table->string('target_db')->nullable();
            $table->integer('tables_count')->default(0);
            $table->integer('records_count')->default(0);
            $table->decimal('file_size', 10, 2)->default(0);
            $table->text('notes')->nullable();
            $table->enum('status', ['pending', 'processing', 'completed', 'failed'])->default('pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('backups');
    }
};