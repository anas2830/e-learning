<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEduAssignmentSubmissionAttachmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('edu_assignment_submission_attachments', function (Blueprint $table) {
            $table->id();
            $table->integer('assignment_submission_id')->comment = 'PK = edu_assignment_submissions.id';
            $table->string('file_name');
            $table->string('file_original_name');
            $table->string('size');
            $table->string('extention');
            $table->integer('created_by')->comment = 'PK = users.id';
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
        Schema::dropIfExists('edu_assignment_submission_attachments');
    }
}
