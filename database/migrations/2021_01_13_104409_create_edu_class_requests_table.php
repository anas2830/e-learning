<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEduClassRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('edu_class_requests', function (Blueprint $table) {
            $table->id();
            $table->integer('batch_id')->comment = 'PK = edu_assign_batches.id';
            $table->integer('assign_batch_class_id')->comment = 'PK = edu_course_assign_classes.id';
            $table->text('request_reasons');
            $table->tinyInteger('approve_status')->default(0)->comment = '1=Approved, 0=Pending';
            $table->integer('supported_by')->nullable()->comment = 'PK = edu_teachers.id';
            $table->text('response')->nullable();
            $table->text('class_link')->nullable();
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
        Schema::dropIfExists('edu_class_requests');
    }
}
