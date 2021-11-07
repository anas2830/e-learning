<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEduArchiveQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('edu_archive_questions', function (Blueprint $table) {
            $table->id();
            $table->text('question');
            $table->integer('course_id')->comment = 'pk=edu_courses.id';
            $table->integer('class_id')->comment = 'pk=edu_course_assign_classes.id';
            $table->tinyInteger('answer_type')->comment = 'pk=edu_answer_type.id';
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
        Schema::dropIfExists('edu_archive_questions');
    }
}
