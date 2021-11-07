<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEduAnswersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('edu_answers', function (Blueprint $table) {
            $table->id();
            $table->integer('question_id')->comment = 'pk = edu_archive_questions.id';
            $table->string('answer');
            $table->tinyInteger('true_answer')->comment = '1=True, 0=False';
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
        Schema::dropIfExists('edu_answers');
    }
}
