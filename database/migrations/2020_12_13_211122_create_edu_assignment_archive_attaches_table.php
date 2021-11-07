<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEduAssignmentArchiveAttachesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('edu_assignment_archive_attaches', function (Blueprint $table) {
            $table->id();
            $table->integer('assignment_archive_id')->comment = 'PK = edu_assignment_archives.id';
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
        Schema::dropIfExists('edu_assignment_archive_attaches');
    }
}
