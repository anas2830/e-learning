<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEduZoomAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('edu_zoom_accounts', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('account_type')->comment = '1=Teacher, 2=Support';
            $table->string('name')->nullable();
            $table->string('email');
            $table->string('password');
            $table->text('token');
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
        Schema::dropIfExists('edu_zoom_accounts');
    }
}
