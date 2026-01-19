    <?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('mbti_questions', function (Blueprint $table) {
            $table->id();
            $table->enum('dimension', ['EI', 'SN', 'TF', 'JP']); // کەڵکی ئەو 4 بەشە
            $table->string('side'); // 'E', 'I', 'S', 'N', 'T', 'F', 'J', 'P'
            $table->text('question_ku'); // پرسیار بە کوردی
            $table->text('question_en')->nullable(); // پرسیار بە ئینگلیزی
            $table->integer('order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mbti_questions');
    }
};
