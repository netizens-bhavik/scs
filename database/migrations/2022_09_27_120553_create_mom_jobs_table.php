<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMomJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mom_jobs', function (Blueprint $table) {
            $table->id();
            $table->integer('mom_id')->nullable();
            $table->date('j_date')->nullable();
            $table->string('job_category')->nullable();
            $table->integer('quantity')->nullable();
            $table->string('job_description')->nullable();
            $table->enum('job_status', ['1', '2', '3', '4'])->comment('1-Ongoing, 2-On Hold, 3-Completed, 4-Cancel')->nullable();
            $table->date('status_date')->nullable();
            $table->integer('created_by')->nullable();
            $table->integer('modified_by')->nullable();
            $table->tinyInteger('is_deleted')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mom_jobs');
    }
}
