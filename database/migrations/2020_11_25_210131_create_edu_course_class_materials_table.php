<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEduCourseClassMaterialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('edu_course_class_materials', function (Blueprint $table) {
            $table->id();
            $table->integer('course_id')->comment = 'PK = edu_courses.id';
            $table->integer('class_id')->comment = 'PK = edu_course_assign_classes.id';
            $table->string('video_id');
            $table->string('video_title');
            $table->integer('video_duration');
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
        Schema::dropIfExists('edu_course_class_materials');
    }
}
