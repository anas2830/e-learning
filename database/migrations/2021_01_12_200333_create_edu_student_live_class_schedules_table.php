<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEduStudentLiveClassSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('edu_student_live_class_schedules', function (Blueprint $table) {
            $table->id();
            $table->integer('zoom_acc_id')->comment = 'Pk = edu_zoom_accounts.id';
            $table->integer('assign_batch_classes_id')->comment = 'Pk = edu_assign_batch_classes.id';
            $table->integer('day_dt');
            $table->date('start_date');
            $table->time('start_time');
            $table->time('end_time');
            $table->integer('hour');
            $table->integer('min');
            $table->integer('duration');
            $table->string('meeting_id');
            $table->string('host_id');
            $table->text('start_url');
            $table->text('join_url');
            $table->string('timezone')->comment = 'Zoom Timezone';
            $table->tinyInteger('type')->comment = 'Zoom Meeting Type';
            $table->integer('created_by')->comment = 'Pk = 	edu_teachers.id';;
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
        Schema::dropIfExists('edu_student_live_class_schedules');
    }
}
