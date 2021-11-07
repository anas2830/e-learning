<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEduStudentWiseClassAssignmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('edu_student_wise_class_assignments', function (Blueprint $table) {
            $table->id();
            $table->integer('student_id')->comment = 'Pk = users.id';
            $table->integer('batch_id')->comment = 'Pk = edu_assign_batches.id';
            $table->integer('course_id')->comment = 'Pk = edu_courses.id';
            $table->integer('assign_batch_classes_id')->comment = 'Pk = edu_assign_batch_classes.id';
            $table->integer('class_assignment_id')->comment = 'Pk = edu_class_assignments.id';
            $table->integer('created_by')->comment = 'Pk = edu_teachers.id';;
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
        Schema::dropIfExists('edu_student_wise_class_assignments');
    }
}
