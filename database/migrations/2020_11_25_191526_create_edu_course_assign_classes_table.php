<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEduCourseAssignClassesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('edu_course_assign_classes', function (Blueprint $table) {
            $table->id();
            $table->integer('course_id')->comment = 'PK = edu_courses.id';
            $table->string('class_name');
            $table->text('class_overview');
            $table->text('class_resource');
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
        Schema::dropIfExists('edu_course_assign_classes');
    }
}
