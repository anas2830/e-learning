<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEduStudentSuccessStoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('edu_student_success_stories', function (Blueprint $table) {
            $table->id();
            $table->integer('course_id')->comment = 'PK = edu_courses.id';
            $table->integer('batch_id')->comment = 'PK = edu_assign_batches.id';
            $table->float('work_amount', 10,2);
            $table->string('marketplace_name');
            $table->text('own_comment')->nullable();
            $table->string('work_screenshort', 50);
            $table->tinyInteger('approve_status')->default(0)->comment = '1=Yes, 0=No';
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
        Schema::dropIfExists('edu_student_success_stories');
    }
}
