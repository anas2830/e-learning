<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEduAnswerTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('edu_answer_types', function (Blueprint $table) {
            $table->id();
            $table->string('answer_type');
        });

        DB::table('edu_answer_types')->insert(array(
            array(
                'id'=> 1, 
                'answer_type'=> 'True/False'
            ), 
            array(
                'id'=> 2, 
                'answer_type'=> 'MCQ [Single Correct Answer]' 
            ),
            array(
                'id'=> 3, 
                'answer_type'=> 'MCQ [Multiple Correct Answer]'
            )
        ));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('edu_answer_types');
    }
}
