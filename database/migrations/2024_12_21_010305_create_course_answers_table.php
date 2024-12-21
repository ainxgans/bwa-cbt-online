<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('course_answers', function (Blueprint $table) {
            $table->id();
            $table->string('answer');
            $table->foreignId('course_question_id')->constrained('course_questions');
            $table->boolean('is_correct');
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('course_answers');
    }
};
