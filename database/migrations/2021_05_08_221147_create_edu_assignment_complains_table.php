<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEduAssignmentComplainsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('edu_assignment_complains', function (Blueprint $table) {
            $table->id();
            $table->integer('taken_assignment_id')->comment = 'PK = edu_taken_assignments.id';
            $table->date('complain_date');
            $table->text('complain');
            $table->integer('complain_from');
            $table->integer('complain_to');
            $table->tinyInteger('complain_to_type')->default(0)->comment = '0=Teacher,1=Support';
            $table->tinyInteger('complain_status')->comment = '1=pending,2=complete';
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
        Schema::dropIfExists('edu_assignment_complains');
    }
}
