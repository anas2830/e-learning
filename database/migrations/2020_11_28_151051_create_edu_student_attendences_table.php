<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEduStudentAttendencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('edu_student_attendences', function (Blueprint $table) {  
            $table->id();
            $table->integer('batch_id')->comment = 'PK = edu_assign_batches.id';
            $table->integer('course_id')->comment = 'PK = edu_courses.id';
            $table->integer('class_id')->comment = 'PK = edu_assign_batch_classes.id';
            $table->integer('student_id')->comment = 'PK = edu_students.id';
            $table->tinyInteger('is_attend')->default(0)->comment = '1=Present, 0=Absent';
            $table->integer('mark')->default(0)->comment = 'Class Performance Mark';
            $table->string('remark')->nullable();
            $table->integer('user_type')->default(1)->comment = '1=Teacher, 2=Provider';
            $table->integer('created_by')->comment = 'PK = 	edu_teachers.id / PK = 	edu_provider_users.id';
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
        Schema::dropIfExists('edu_student_attendences');
    }
}
