<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEduFilePondsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('edu_file_ponds', function (Blueprint $table) {
            $table->id();
            $table->string('file_name');
            $table->string('folder_name');
            $table->string('file_original_name');
            $table->string('size');
            $table->string('extention');
            $table->integer('created_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->tinyInteger('valid')->default(1)->comment = '1=Yes, 0=No';

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('edu_file_ponds');
    }
}
