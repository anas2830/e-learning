<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEduStudentSupportRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('edu_student_support_requests', function (Blueprint $table) {
            $table->id();
            $table->integer('category_id')->comment = 'PK = edu_support_categories.id';
            $table->integer('batch_id')->comment = 'PK = edu_assign_batches.id';
            $table->integer('course_id')->comment = 'PK = edu_courses.id';
            $table->string('request_title');
            $table->text('request_details');
            $table->integer('created_by')->comment = 'PK = users.id';
            $table->tinyInteger('approve_status')->default(0)->comment = '1=Approved, 0=Pending';
            $table->integer('supported_by')->nullable()->comment = 'PK = edu_supports.id';
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
        Schema::dropIfExists('edu_student_support_requests');
    }
}
