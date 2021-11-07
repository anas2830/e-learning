<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEduStdSuccessStoryReactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('edu_std_success_story_reactions', function (Blueprint $table) {
            $table->id();
            $table->integer('success_story_id')->comment = 'PK = edu_student_success_stories.id';
            $table->tinyInteger('react_status')->comment = '1=Yes, 0=No';
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
        Schema::dropIfExists('edu_std_success_story_reactions');
    }
}
