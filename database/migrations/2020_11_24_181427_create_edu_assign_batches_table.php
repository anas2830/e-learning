<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEduAssignBatchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('edu_assign_batches', function (Blueprint $table) {
            $table->id();
            $table->integer('course_id')->comment = 'PK = edu_courses.id';
            $table->integer('teacher_id')->nullable()->comment = 'PK=edu_teachers.id';
            $table->string('captain_ids')->nullable()->comment = 'PK=users.id';
            $table->string('instructor_chat_link')->nullable()->comment = 'fb messenger link';
            $table->string('batch_no');
            $table->date('start_date')->nullable();
            $table->time('start_time')->nullable();
            $table->text('batch_fb_url')->nullable();
            $table->tinyInteger('active_status')->default(1)->comment = '1=active, 0=inactive';
            $table->tinyInteger('complete_status')->default(0)->comment = '1=Yes, 0=No';
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
        Schema::dropIfExists('edu_assign_batches');
    }
}
