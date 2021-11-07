<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEduActivityNotifiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('edu_activity_notifies', function (Blueprint $table) {
            $table->id();
            $table->integer('batch_id')->nullable()->comment = 'FK = edu_assign_batches.id';
            $table->integer('assign_batch_class_id')->nullable()->comment = 'FK = edu_assign_batch_classes.id';
            $table->integer('course_id')->comment = 'FK = edu_courses.id';
            $table->integer('notify_type')->comment = '1=student_teacher_widget,2=assignment_review';
            $table->integer('student_id')->nullable()->comment = 'FK = users.id';
            $table->date('notify_date');
            $table->time('notify_time');
            $table->string('notify_title');
            $table->text('notify_link');
            $table->tinyInteger('created_type')->comment = '1=Provider,2=Teacher,3=Reviewer,4=Support';
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
        Schema::dropIfExists('edu_activity_notifies');
    }
}
