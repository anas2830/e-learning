<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEduBatchStudentRemarksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('edu_batch_student_remarks', function (Blueprint $table) {
            $table->id();
            $table->integer('batch_id')->comment = 'FK = edu_assign_batches.id';
            $table->integer('course_id')->comment = 'FK = edu_courses.id';
            $table->integer('student_id')->comment = 'FK = users.id';
            $table->string('remark')->nullable();
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
        Schema::dropIfExists('edu_batch_student_remarks');
    }
}
