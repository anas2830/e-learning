<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEduAssignmentCommentAttachmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('edu_assignment_comment_attachments', function (Blueprint $table) {
            $table->id();
            $table->integer('assignment_comment_id')->comment = 'PK = edu_assignment_comments.id';
            $table->string('file_name')->nullable();
            $table->string('file_original_name')->nullable();
            $table->string('size')->nullable();
            $table->string('extention')->nullable();
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
        Schema::dropIfExists('edu_assignment_comment_attachments');
    }
}
