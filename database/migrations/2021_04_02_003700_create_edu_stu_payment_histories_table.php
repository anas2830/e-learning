<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEduStuPaymentHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('edu_stu_payment_histories', function (Blueprint $table) {
            $table->id();
            $table->integer('payment_system_id')->comment = 'Pk = payment_systems.id';
            $table->integer('serial_no');
            $table->float('amount', 10,2);
            $table->integer('assign_batch_std_id')->comment = 'Pk = edu_assign_batch_students.id';
            $table->tinyInteger('is_running')->comment = '1=completed, 2=running, 3=upcomming';
            $table->tinyInteger('paid_from')->nullable()->comment = '1=From Student, 2=Manual';
            $table->date('payment_date')->nullable();
            $table->date('start_date');
            $table->date('end_date');
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
        Schema::dropIfExists('edu_stu_payment_histories');
    }
}
