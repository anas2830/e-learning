<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEduStudentExamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('edu_student_exams', function (Blueprint $table) {
            $table->id();
            $table->integer('exam_config_id')->comment = 'pk= edu_exam_configs.id';
            $table->integer('batch_id')->comment = 'pk= edu_assign_batches.id';
            $table->integer('assign_batch_class_id')->comment = 'pk= edu_assign_batch_classes.id';
            $table->integer('course_id')->comment = 'pk= edu_courses.id';
            $table->integer('course_class_id')->comment = 'pk= edu_course_assign_classes.id';
            $table->integer('student_id')->comment = 'pk= users.id';
            $table->integer('exam_duration')->comment = 'in minute'; 
            $table->integer('taken_duration')->comment = 'in strtoTime'; 
            $table->integer('total_questions');
            $table->integer('total_answer');
            $table->integer('total_correct_answer');
            $table->integer('per_question_mark');
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
        Schema::dropIfExists('edu_student_exams');
    }
}
