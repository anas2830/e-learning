<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEduExamConfigsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('edu_exam_configs', function (Blueprint $table) {
            $table->id();
            $table->integer('batch_id')->comment = 'pk=edu_assign_batches.id';
            $table->integer('assign_batch_class_id')->comment = 'pk=edu_assign_batch_classes.id';
            $table->text('exam_overview');
            $table->integer('exam_duration');
            $table->string('questions');
            $table->integer('total_question');
            $table->integer('per_question_mark');
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
        Schema::dropIfExists('edu_exam_configs');
    }
}
