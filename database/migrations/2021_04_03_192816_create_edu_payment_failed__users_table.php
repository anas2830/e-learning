<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEduPaymentFailedUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('edu_payment_failed', function (Blueprint $table) {
            $table->id();
            $table->string('tran_id');
            $table->integer('payment_history_id')->comment = 'PK = edu_stu_payment_histories.id';
            $table->integer('course_id')->comment = 'edu_courses.id';
            $table->integer('std_id')->comment = 'users.id';
            $table->tinyInteger('payment_gateway')->comment = '1=SSL Comerz, 2=2 Check Out';
            $table->tinyInteger('failed_reason')->comment ="1=Failed to connect with SSLCOMMERZ, 2=Transaction Failed, 3=Failed Data Tempered, 4=Failed Amount Tempered ";
            $table->text('payment_response')->comment = "Serialize Array (Response Value from SSL Comerz or 2 Check Out) ";
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
        Schema::dropIfExists('edu_payment_failed__users');
    }
}
