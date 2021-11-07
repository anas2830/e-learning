<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEduEventSmsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('edu_event_sms', function (Blueprint $table) {
            $table->id();
            $table->integer('type')->comment = '1=register student,2=assign student,3=class schedule change,4=absent class';
            $table->string('message');
            $table->tinyInteger('status')->comment = '1=active ,0=inactive';
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
        Schema::dropIfExists('edu_event_sms');
    }
}
