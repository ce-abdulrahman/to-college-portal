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
        Schema::create('centers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // if role is center
            $table->text('address')->nullable();
            $table->text('description')->nullable();            
            $table->boolean('ai_rank')->default(0);
            $table->boolean('gis')->default(0);
            $table->boolean('all_departments')->default(0);
            $table->string('referral_code')->default(0)->comment('Get Relation in User Role Center column to rand_code');
            $table->timestamps();
        });
    }

    /** 
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('centers');
    }
};
