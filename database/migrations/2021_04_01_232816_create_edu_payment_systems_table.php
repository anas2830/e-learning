<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEduPaymentSystemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('edu_payment_systems', function (Blueprint $table) {
            $table->id();
            $table->string('payment_type');
        });

        DB::table('edu_payment_systems')->insert(array(
            array(
                'id'=> 1, 
                'payment_type'=> 'Full Payment'
            ),
            array(
                'id'=> 2, 
                'payment_type'=> 'Installment Payment'
            ),
            array(
                'id'=> 3, 
                'payment_type'=> 'Monthly Payment'
            )
        ));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('edu_payment_systems');
    }
}
