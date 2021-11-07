<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEduAssignBatchClassesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('edu_assign_batch_classes', function (Blueprint $table) {
            $table->id();
            $table->integer('batch_id')->comment = 'PK = edu_assign_batches.id';
            $table->integer('course_id')->comment = 'PK = edu_courses.id';
            $table->integer('class_id')->comment = 'PK = edu_course_assign_classes.id';
            $table->date('start_date')->nullable();
            $table->time('start_time')->nullable();
            $table->date('end_date')->nullable();
            $table->tinyInteger('complete_status')->default(3)->comment = '1=completed,2=running,3=upcomming';
            $table->text('drive_link')->nullable();
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
        Schema::dropIfExists('edu_assign_batch_classes');
    }
}
