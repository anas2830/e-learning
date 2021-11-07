<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEduAssignmentArchivesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('edu_assignment_archives', function (Blueprint $table) {
            $table->id();
            $table->integer('course_id')->comment = 'PK = edu_courses.id';
            $table->integer('course_class_id')->comment = 'PK = edu_course_assign_classes.id';
            $table->string('title');
            $table->mediumText('overview')->nullable();
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
        Schema::dropIfExists('edu_assignment_archives');
    }
}
