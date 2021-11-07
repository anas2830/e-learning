<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEduClassLectureLinksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('edu_class_lecture_links', function (Blueprint $table) {
            $table->id();
            $table->integer('assign_batch_class_id')->comment = 'PK = edu_assign_batch_classes.id';
            $table->string('video_id');
            $table->string('video_title');
            $table->integer('video_duration');
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
        Schema::dropIfExists('edu_class_lecture_links');
    }
}
