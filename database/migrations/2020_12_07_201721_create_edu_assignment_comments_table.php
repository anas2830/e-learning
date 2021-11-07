<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEduAssignmentCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('edu_assignment_comments', function (Blueprint $table) {
            $table->id();
            $table->integer('assignment_submission_id')->comment = 'PK = edu_assignment_submissions.id';
            $table->integer('class_assignments_id')->comment = 'PK = edu_class_assignments.id';
            $table->integer('batch_id')->comment = 'PK = edu_assign_batches.id';
            $table->integer('course_id')->comment = 'PK = edu_courses.id';
            $table->integer('assign_batch_class_id')->comment = 'PK = edu_assign_batch_classes.id';
            $table->integer('student_id')->comment = 'PK = users.id';
            $table->mediumText('comment');
            $table->string('file_name')->nullable();
            $table->string('file_original_name')->nullable();
            $table->string('size')->nullable();
            $table->string('extention')->nullable();
            $table->tinyInteger('comment_by_type')->default(0)->comment = '0=Teacher, 1=Reviewer, 2=Support';
            $table->integer('created_by')->comment = 'PK = edu_teachers.id/users.id/edu_supports.id';
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
        Schema::dropIfExists('edu_assignment_comments');
    }
}
