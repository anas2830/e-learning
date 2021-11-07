<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEduProviderUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('edu_provider_users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('address');
            $table->string('phone', 50)->nullable();
            $table->string('image')->nullable();
            $table->tinyInteger('email_verified')->comment = '1=Yes, 0=No';
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
            $table->string('status')->default('Active');
            $table->tinyInteger('valid')->comment = '1=Yes, 0=No';
        });

        DB::table('edu_provider_users')->insert(
            array('id'=> 1, 'name'=> 'Md.Provider', 'email'=> 'provider@gmail.com', 'address'=> 'Dhaka', 'phone'=> null, 'image'=> null, 'email_verified'=> 1, 'email_verified_at'=> null, 'password'=> '$2y$10$QA2tpIzuEbbRf//TxfSj.OkRg4.4k/PiLI8TE2IpNBqJuC9A9nZH6', 'remember_token'=> null, 'created_at'=> '2020-10-14 17:38:27', 'updated_at'=> '2020-10-14 17:38:27', 'deleted_at'=> null, 'status'=> 'Active', 'valid'=> 1)
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('edu_provider_users');
    }
}
