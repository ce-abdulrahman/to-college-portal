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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade')->unique();
            $table->float('mark', 6, 3);
            $table->string('province');
            $table->enum('type', ['زانستی','وێژەیی'])->default('زانستی');
            $table->enum('gender', ['نێر','مێ'])->default('نێر');
            $table->integer('year');
            $table->string('referral_code')->nullable()->comment('Get Relation in User Role Student column to rand_code');
            $table->boolean('status')->default(0);
            $table->string('mbti_type')->nullable()->index();
            $table->boolean('ai_rank')->default(0);
            $table->boolean('gis')->default(0);
            $table->boolean('all_departments')->default(0);
            $table->decimal('lat', 10, 7)->nullable();
            $table->decimal('lng', 10, 7)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
