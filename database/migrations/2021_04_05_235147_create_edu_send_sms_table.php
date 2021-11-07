<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEduSendSmsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('edu_send_sms', function (Blueprint $table) {
            $table->id();
            $table->integer('batch_id')->nullable()->comment = 'pk= edu_assign_batches.id';
            $table->integer('course_id')->nullable()->comment = 'pk= edu_courses.id';
            $table->integer('sms_receiver_id')->comment = '0 = all, 1 = selected user';
            $table->tinyInteger('sms_receiver_type')->comment = '1=student, 2=teacher, 3=support';
            $table->string('message');
            $table->date('date'); 
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
        Schema::dropIfExists('edu_send_sms');
    }
}
