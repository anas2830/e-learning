<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEduAssignBatchStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('edu_assign_batch_students', function (Blueprint $table) {
            $table->id();
            $table->integer('batch_id')->comment = 'PK = edu_assign_batches.id';
            $table->integer('course_id')->comment = 'PK = edu_courses.id';
            $table->integer('student_id')->comment = 'PK = edu_users.id';
            $table->integer('payment_system_id')->nullabe()->comment = 'PK = edu_payment_systems.id';
            $table->tinyInteger('is_running')->default(1)->comment = '1=Yes, 0=No';
            $table->tinyInteger('active_status')->default(1)->comment = '1=Yes, 0=No';
            $table->tinyInteger('is_freez')->default(0)->comment = '1=Yes, 0=No';
            $table->text('freez_reason')->nullable();
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
        Schema::dropIfExists('edu_assign_batch_students');
    }
}
