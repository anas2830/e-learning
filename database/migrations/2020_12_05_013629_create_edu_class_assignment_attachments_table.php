<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEduClassAssignmentAttachmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('edu_class_assignment_attachments', function (Blueprint $table) {
            $table->id();
            $table->integer('class_assignment_id')->comment = 'PK = edu_class_assignments.id';
            $table->integer('archive_attach_id')->comment = 'PK = edu_assignment_archive_attaches.id';
            $table->string('file_name');
            $table->string('file_original_name');
            $table->string('size');
            $table->string('extention');
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
        Schema::dropIfExists('edu_class_assignment_attachments');
    }
}
