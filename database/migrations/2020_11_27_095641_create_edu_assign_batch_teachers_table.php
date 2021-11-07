<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEduAssignBatchTeachersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('edu_assign_batch_teachers', function (Blueprint $table) {
            $table->id();
            $table->integer('batch_id')->comment = 'PK = edu_assign_batches.id';
            $table->integer('teacher_id')->comment = 'PK = edu_teachers.id';
            $table->tinyInteger('active_status')->default(1)->comment = '1=Yes, 0=No';
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
        Schema::dropIfExists('edu_assign_batch_teachers');
    }
}
