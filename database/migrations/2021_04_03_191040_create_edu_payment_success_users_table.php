<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEduPaymentSuccessUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('edu_payment_success', function (Blueprint $table) {
            $table->id();
            $table->string('tran_id');
            $table->integer('payment_history_id')->comment = 'PK = edu_stu_payment_histories.id';
            $table->integer('course_id')->comment = 'edu_courses.id';
            $table->integer('std_id')->comment = 'users.id';
            $table->float('amount', 10,2);
            $table->float('store_amount', 10,2);
            $table->string('currency');
            $table->tinyInteger('payment_gateway')->comment = '1=SSL Comerz, 2=2 Check Out';
            $table->string('payment_method')->comment = "Bank or Method Name";
            $table->text('post_return')->comment = "Serialize Array (Return Post Value from SSL Comerz) ";
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
        Schema::dropIfExists('edu_payment_success_users');
    }
}
