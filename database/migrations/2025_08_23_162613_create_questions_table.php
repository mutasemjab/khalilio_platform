<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('exam_id');
            $table->enum('type', ['multiple_choice', 'true_false', 'essay', 'fill_blank']);
            $table->text('question_text');
            $table->string('question_image')->nullable();
            $table->decimal('grade', 5, 2);
            $table->integer('order')->default(0);
            $table->boolean('is_required')->default(true);
            $table->json('options')->nullable(); // For multiple choice options
            $table->json('correct_answers')->nullable(); // Store correct answers
            $table->text('explanation')->nullable(); // Explanation for correct answer
            $table->timestamps();

            $table->foreign('exam_id')->references('id')->on('exams')->onDelete('cascade');
            $table->index(['exam_id', 'order']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('questions');
    }
};
