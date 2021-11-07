<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEduStudentVideoWatchInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('edu_student_video_watch_infos', function (Blueprint $table) {
            $table->id();
            $table->integer('student_id')->comment = 'PK = users.id';
            $table->integer('batch_id')->comment = 'PK = edu_assign_batches.id';
            $table->integer('course_id')->comment = 'PK = edu_courses.id';
            $table->integer('assign_batch_class_id')->comment = 'PK = edu_assign_batch_classes.id';
            $table->integer('material_id')->comment = 'PK = edu_course_class_materials.id';
            $table->integer('watch_time')->default(0)->comment = 'In Seconds';
            $table->integer('video_duration')->default(0)->comment = 'edu_course_class_materials.video_duration In Seconds';
            $table->tinyInteger('is_complete')->default(0)->comment = '1=Yes, 0=No';
            $table->date('date');
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
        Schema::dropIfExists('edu_student_video_watch_infos');
    }
}
