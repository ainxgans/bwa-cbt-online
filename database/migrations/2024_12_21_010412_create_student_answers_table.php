<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('student_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('course_question_id')->constrained('course_questions');
            $table->string('answer');
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('student_answers');
    }
};
