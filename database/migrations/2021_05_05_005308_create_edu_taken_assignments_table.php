<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEduTakenAssignmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('edu_taken_assignments', function (Blueprint $table) {
            $table->id();
            $table->integer('batch_id')->comment = 'PK = edu_assign_batches.id';
            $table->integer('course_id')->comment = 'PK = edu_courses.id';
            $table->integer('assign_batch_class_id')->comment = 'PK = edu_assign_batch_classes.id';
            $table->integer('class_assignment_id')->comment = 'PK = edu_class_assignments.id';
            $table->integer('assignment_submission_id')->comment = 'PK = edu_assignment_submissions.id';
            $table->integer('student_id')->comment = 'PK = edu_students.id';
            $table->date('taken_date');
            $table->time('taken_time');
            $table->date('expire_date');
            $table->time('expire_time');
            $table->tinyInteger('review_status')->comment = '1=pending,2=reviewd,3=under revision';
            $table->tinyInteger('taken_by_type')->default(0)->comment = '0=Student, 1=Support';
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
        Schema::dropIfExists('edu_taken_assignments');
    }
}
