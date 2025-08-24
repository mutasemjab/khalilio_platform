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
        Schema::create('exams', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->unsignedBigInteger('category_exam_id');
            $table->integer('duration_minutes'); // Exam duration in minutes
            $table->datetime('start_time');
            $table->datetime('end_time');
            $table->decimal('total_grade', 5, 2)->default(0);
            $table->decimal('pass_grade', 5, 2)->default(0);
            $table->integer('max_attempts')->default(1);
            $table->boolean('randomize_questions')->default(false);
            $table->boolean('show_results_immediately')->default(true);
            $table->boolean('is_active')->default(true);
            $table->json('instructions')->nullable(); // Exam instructions
            $table->timestamps();

            $table->foreign('category_exam_id')->references('id')->on('category_exams')->onDelete('cascade');
            $table->index(['is_active', 'start_time', 'end_time']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('exams');
    }
};
