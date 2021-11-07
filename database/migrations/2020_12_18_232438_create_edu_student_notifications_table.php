<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEduStudentNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('edu_student_notifications', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('overview');
            $table->integer('type')->comment = '1=course,2=batch,3=student';
            $table->integer('batch_id')->nullable()->comment = 'edu_assign_batchse.id';
            $table->integer('course_id')->comment = 'edu_courses.id';
            $table->integer('student_id')->nullable()->comment = 'edu_users.id';
            $table->integer('created_by')->comment = 'PK = edu_provider_users.id';
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
        Schema::dropIfExists('edu_student_notifications');
    }
}
