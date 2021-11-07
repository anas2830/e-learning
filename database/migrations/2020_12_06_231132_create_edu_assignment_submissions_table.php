<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEduAssignmentSubmissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('edu_assignment_submissions', function (Blueprint $table) {
            $table->id();
            $table->integer('assignment_id')->comment = 'PK = edu_class_assignments.id';
            $table->text('comment')->nullable();
            $table->date('submission_date');
            $table->time('submission_time');
            $table->integer('late_submit')->comment = '1=Yes, 0=No';
            $table->integer('is_improve')->comment = '1=Yes, 0=No';
            $table->integer('mark')->default(0);
            $table->tinyInteger('mark_from')->default(0)->comment = '0=General Check, 1=After Complain/Revision';
            $table->integer('mark_by')->default(0)->comment = 'PK = edu_teachers.id/users.id/edu_supports.id';
            $table->tinyInteger('mark_by_type')->default(0)->comment = '0=Teacher, 1=Reviewer, 2=Support';
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
        Schema::dropIfExists('edu_assignment_submissions');
    }
}
