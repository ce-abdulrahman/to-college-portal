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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('code')->unique();   // instead of email
            $table->string('password');
            $table->string('phone')->nullable();
            $table->enum('role', ['admin', 'center', 'teacher', 'student'])->default('student');
            $table->string('rand_code')->unique()->default(0);   // instead of email
            $table->boolean('status')->default(1);
            $table->rememberToken(); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
