<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEduPaymentTempUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('edu_payment_temp', function (Blueprint $table) {
            $table->id();
            $table->string('tran_id');
            $table->integer('payment_history_id')->comment = 'PK = edu_stu_payment_histories.id';
            $table->integer('course_id')->comment = 'PK = edu_courses.id';
            $table->integer('std_id')->comment = 'users.id';
            $table->float('local_amount', 10,2);
            $table->float('foreign_amount', 10,2);
            $table->text('post_return')->comment = "Serialize Array (Return Post Value from SSL Comerz";
            $table->string('work_status')->comment ="1=Worked, 0=Not Worked ";
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
        Schema::dropIfExists('edu_payment_temp__users');
    }
}
