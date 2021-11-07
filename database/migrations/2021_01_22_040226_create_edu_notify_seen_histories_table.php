<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEduNotifySeenHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('edu_notify_seen_histories', function (Blueprint $table) {
            $table->id();
            $table->integer('notify_id')->comment = 'FK = edu_activity_notifies.id';
            $table->integer('assign_batch_class_id')->nullable()->comment = 'FK = edu_assign_batch_classes.id';
            $table->integer('created_by')->comment = "FK = users.id";
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
        Schema::dropIfExists('edu_notify_seen_histories');
    }
}
