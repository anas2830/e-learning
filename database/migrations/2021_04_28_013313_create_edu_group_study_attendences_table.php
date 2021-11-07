<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEduGroupStudyAttendencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('edu_group_study_attendences', function (Blueprint $table) {
            $table->id();
            $table->integer('batch_id')->comment = 'PK = edu_assign_batches.id';
            $table->integer('course_id')->comment = 'PK = edu_courses.id';
            $table->integer('assign_batch_class_id')->comment = 'PK = edu_assign_batch_classes.id';
            $table->integer('student_id')->comment = 'PK = edu_students.id';
            $table->tinyInteger('is_attend')->default(0)->comment = '1=Present, 0=Absent';
            $table->string('remark')->nullable();
            $table->integer('created_by')->comment = 'users.id';
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
        Schema::dropIfExists('edu_group_study_attendences');
    }
}
