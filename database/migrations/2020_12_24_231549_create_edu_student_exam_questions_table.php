<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEduStudentExamQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('edu_student_exam_questions', function (Blueprint $table) {
            $table->id();
            $table->integer('student_exam_id')->comment = 'pk = edu_student_exams.id';
            $table->integer('question_id')->comment = 'pk = edu_archive_questions.id';
            $table->text('answer')->comment = 'serialized array, pk = edu_answers.id';
            $table->integer('answered')->comment = '1=yes,0=no';
            $table->integer('corrected')->comment = '1=yes,0=no';
            $table->integer('created_by');
            $table->timestamps();
            $table->softDeletes();
            $table->tinyInteger('valid')->comment = '1=Yes, 0=No';

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('edu_student_exam_questions');
    }
}
