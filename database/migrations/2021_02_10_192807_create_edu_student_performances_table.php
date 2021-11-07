<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEduStudentPerformancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('edu_student_performances', function (Blueprint $table) {
            $table->id();
            $table->integer('student_id')->comment = 'Pk = users.id';
            $table->integer('course_id')->comment = 'Pk = edu_courses.id';
            $table->integer('course_class_id')->comment = 'Pk = edu_course_assign_classes.id';
            $table->integer('batch_id')->comment = 'Pk = edu_assign_batches.id';
            $table->integer('assign_batch_classes_id')->comment = 'Pk = edu_assign_batch_classes.id';
            $table->float('practice_time',10,2);
            $table->float('video_watch_time',10,2);
            $table->float('attendence',10,2);
            $table->float('class_mark',10,2);
            $table->float('assignment',10,2);
            $table->float('quiz',10,2);
            $table->integer('created_by')->comment = "pk = edu_teachers.id";
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
        Schema::dropIfExists('edu_student_performances');
    }
}
