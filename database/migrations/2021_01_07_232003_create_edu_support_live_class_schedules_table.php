<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEduSupportLiveClassSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('edu_support_live_class_schedules', function (Blueprint $table) {
            $table->id();
            $table->integer('zoom_acc_id')->comment = 'Pk = edu_zoom_accounts.id';
            $table->integer('std_support_req_id')->comment = 'Pk = edu_student_support_requests.id';
            $table->integer('support_cat_id')->comment = 'Pk = edu_support_categories.id';
            $table->integer('student_id')->comment = 'Pk = users.id';
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
            $table->integer('created_by')->comment = 'Pk = edu_supports.id';
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
        Schema::dropIfExists('edu_support_live_class_schedules');
    }
}
