<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->string('spoken_with');
            $table->integer('temp_lead_id')->nullable();
            $table->integer('bdm_id')->nullable();
            $table->tinyInteger('lead_status')->comment('1-ready to dispaly director, 2-ready to display BDM, 3- MOM generated/make client')->nullable()->default(1);
            $table->string('contact_no')->nullable();
            $table->string('email')->nullable();
            $table->boolean('is_requirement')->default(0)->comment('0-no, 1-yes')->nullable();
            $table->text('basic_requirement')->nullable();
            $table->integer('created_by');
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
        Schema::dropIfExists('leads');
    }
}
