<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEduStudentProgressTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('edu_student_progress', function (Blueprint $table) {
            $table->id();
            $table->integer('practice_time');
            $table->integer('video_watch_time');
            $table->integer('attendence');
            $table->integer('class_mark');
            $table->integer('assignment');
            $table->integer('quiz');
            $table->tinyInteger('type')->comment = '1=progress';
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
        Schema::dropIfExists('edu_student_progress');
    }
}
