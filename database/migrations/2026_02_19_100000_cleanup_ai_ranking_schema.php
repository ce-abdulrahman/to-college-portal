<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('ai_answers');
        Schema::dropIfExists('ai_questions');
        Schema::dropIfExists('ai_ranking_preferences');
        Schema::dropIfExists('ai_ranking_feedbacks');

        if (!Schema::hasTable('ai_rankings')) {
            return;
        }

        if (!Schema::hasColumn('ai_rankings', 'rank')) {
            Schema::table('ai_rankings', function (Blueprint $table) {
                $table->integer('rank')->nullable()->after('department_id');
            });
        }

        if (!Schema::hasColumn('ai_rankings', 'result_rank')) {
            Schema::table('ai_rankings', function (Blueprint $table) {
                $table->integer('result_rank')->nullable()->after('rank');
            });
        }

        if (Schema::hasColumn('ai_rankings', 'score')) {
            Schema::table('ai_rankings', function (Blueprint $table) {
                $table->dropColumn('score');
            });
        }

        if (Schema::hasColumn('ai_rankings', 'reason')) {
            Schema::table('ai_rankings', function (Blueprint $table) {
                $table->dropColumn('reason');
            });
        }

        if (Schema::hasColumn('ai_rankings', 'match_factors')) {
            Schema::table('ai_rankings', function (Blueprint $table) {
                $table->dropColumn('match_factors');
            });
        }
    }

    public function down(): void
    {
        if (!Schema::hasTable('ai_questions')) {
            Schema::create('ai_questions', function (Blueprint $table) {
                $table->id();
                $table->string('question_ku');
                $table->string('question_en')->nullable();
                $table->string('category');
                $table->json('options')->nullable();
                $table->integer('weight')->default(1);
                $table->json('department_weights')->nullable();
                $table->integer('order')->default(0);
                $table->boolean('status')->default(true);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('ai_answers')) {
            Schema::create('ai_answers', function (Blueprint $table) {
                $table->id();
                $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
                $table->foreignId('question_id')->constrained('ai_questions')->onDelete('cascade');
                $table->text('answer');
                $table->float('score', 5, 2)->default(0);
                $table->timestamps();
                $table->unique(['student_id', 'question_id']);
            });
        }

        if (!Schema::hasTable('ai_ranking_preferences')) {
            Schema::create('ai_ranking_preferences', function (Blueprint $table) {
                $table->id();
                $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
                $table->boolean('consider_personality')->default(true);
                $table->boolean('include_specific_questions')->default(true);
                $table->boolean('prefer_nearby_departments')->default(true);
                $table->boolean('use_mark_bonus')->default(true);
                $table->boolean('mark_bonus_enabled')->default(true);
                $table->json('preferred_systems')->nullable();
                $table->json('gender_filter')->nullable();
                $table->json('field_type_filter')->nullable();
                $table->string('province_filter')->nullable();
                $table->timestamps();
                $table->unique('student_id');
            });
        }

        if (!Schema::hasTable('ai_ranking_feedbacks')) {
            Schema::create('ai_ranking_feedbacks', function (Blueprint $table) {
                $table->id();
                $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
                $table->foreignId('department_id')->constrained('departments')->onDelete('cascade');
                $table->foreignId('ai_ranking_id')->nullable()->constrained('ai_rankings')->nullOnDelete();
                $table->unsignedSmallInteger('selected_rank')->nullable();
                $table->unsignedSmallInteger('ai_rank')->nullable()->index();
                $table->decimal('ai_score', 6, 3)->nullable();
                $table->timestamp('selected_at')->nullable()->index();
                $table->json('metadata')->nullable();
                $table->timestamps();
                $table->unique('student_id');
                $table->index(['department_id', 'selected_at']);
            });
        }

        if (!Schema::hasTable('ai_rankings')) {
            return;
        }

        if (!Schema::hasColumn('ai_rankings', 'score')) {
            Schema::table('ai_rankings', function (Blueprint $table) {
                $table->float('score', 5, 2)->default(0)->after('department_id');
            });
        }

        if (Schema::hasColumn('ai_rankings', 'rank') && Schema::hasColumn('ai_rankings', 'score') && !Schema::hasColumn('ai_rankings', 'reason')) {
            Schema::table('ai_rankings', function (Blueprint $table) {
                $table->text('reason')->nullable()->after('rank');
            });
        }

        if (!Schema::hasColumn('ai_rankings', 'match_factors')) {
            Schema::table('ai_rankings', function (Blueprint $table) {
                $table->json('match_factors')->nullable()->after('reason');
            });
        }

        if (Schema::hasColumn('ai_rankings', 'result_rank')) {
            Schema::table('ai_rankings', function (Blueprint $table) {
                $table->dropColumn('result_rank');
            });
        }
    }
};
