<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEduScheduleDaysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('edu_schedule_days', function (Blueprint $table) {
            $table->id();
            $table->integer('day_id');
            $table->string('day_name');
            $table->string('day_sort_name');
            $table->tinyInteger('dt')->comment = 'Numeric representation of the day of the week';

        });

        DB::table('edu_schedule_days')->insert(array(
            array(
                'id'=> 1, 
                'day_id'=> 1, 
                'day_name'=> 'Sunday', 
                'day_sort_name'=> 'Sun', 
                'dt'=> 0,
            ),
            array(
                'id'=> 2, 
                'day_id'=> 2, 
                'day_name'=> 'Monday', 
                'day_sort_name'=> 'Mon', 
                'dt'=> 1,
            ),
            array(
                'id'=> 3, 
                'day_id'=> 3, 
                'day_name'=> 'Tuesday', 
                'day_sort_name'=> 'Tue', 
                'dt'=> 2,
            ),
            array(
                'id'=> 4, 
                'day_id'=> 4, 
                'day_name'=> 'Wednesday', 
                'day_sort_name'=> 'Wed', 
                'dt'=> 3,
            ),
            array(
                'id'=> 5, 
                'day_id'=> 5, 
                'day_name'=> 'Thursday', 
                'day_sort_name'=> 'Thu', 
                'dt'=> 4,
            ),
            array(
                'id'=> 6, 
                'day_id'=> 6, 
                'day_name'=> 'Friday', 
                'day_sort_name'=> 'Fri', 
                'dt'=> 5,
            ),
            array(
                'id'=> 7, 
                'day_id'=> 7, 
                'day_name'=> 'Saturday', 
                'day_sort_name'=> 'Sat', 
                'dt'=> 6,
            ),
        ));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('edu_schedule_days');
    }
}
