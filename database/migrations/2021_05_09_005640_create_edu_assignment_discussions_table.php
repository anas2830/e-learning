<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEduAssignmentDiscussionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('edu_assignment_discussions', function (Blueprint $table) {
            $table->id();
            $table->integer('taken_assignment_id')->comment = 'PK = edu_taken_assignments.id';
            $table->integer('msg_sl');
            $table->text('message');
            $table->tinyInteger('msg_by_type')->comment = '1 = Student, 2 = Reviewer(std), 3 = Support';
            $table->integer('created_by')->comment = 'PK = users.id/edu_supports.id';
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
        Schema::dropIfExists('edu_assignment_discussions');
    }
}
