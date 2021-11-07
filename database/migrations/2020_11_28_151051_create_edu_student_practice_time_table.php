<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEduStudentPracticeTimeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('edu_student_practice_time', function (Blueprint $table) {
            $table->id();
            $table->integer('student_id')->comment = 'PK = users.id';
            $table->integer('course_id')->comment = 'PK = edu_courses.id';
            $table->integer('batch_id')->comment = 'PK = edu_assign_batches.id';
            $table->date('date');
            $table->bigInteger('total_time');
            $table->bigInteger('resume_time');
            $table->integer('created_by')->comment = 'PK = 	users.id';
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
        Schema::dropIfExists('edu_student_practice_time');
    }
}
