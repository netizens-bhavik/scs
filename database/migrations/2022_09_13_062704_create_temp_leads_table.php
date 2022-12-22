<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTempLeadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('temp_leads', function (Blueprint $table) {
            $table->id();
            $table->string('company_name');
            $table->string('company_phone_no')->nullable();
            $table->integer('company_country_id')->nullable();
            $table->integer('company_city_id')->nullable();
            $table->integer('industry_id')->nullable();
            $table->string('company_email')->nullable();
            $table->string('department')->nullable();
            $table->string('designation')->nullable();
            $table->string('contact_person_name')->nullable();
            $table->string('contact_person_email')->nullable();
            $table->string('contact_person_phone')->nullable();
            $table->date('dob')->nullable();
            $table->text('address')->nullable();
            $table->string('post_box_no')->nullable();
            $table->integer('cp_country_id')->nullable();
            $table->integer('cp_city_id')->nullable();
            $table->string('website_name')->nullable();
            $table->enum('calling_status', ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '10'])
                ->comment('0-not called,1-busy, 2-call latter, 3-called, 4-do not call again, 5-no requirement, 6-not reachable, 7-out of service, 8-ringing, 9-switch off, 10-wrong number')
                ->default('0')->nullable();
            $table->dateTime('recalling_date')->nullable()->comment('date and time when lead will be recalled');
            $table->dateTime('last_call_date')->nullable();
            $table->enum('call_type', ['1', '2'])->comment('1-incoming, 2-outgoing')->nullable();
            $table->integer('tele_caller_id')->nullable();
            $table->integer('last_tele_caller_id')->nullable();
            $table->text('last_call_comment')->nullable();
            $table->boolean('is_assigned')->default(0);
            $table->integer('imported_by')->nullable();
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
        Schema::dropIfExists('temp_leads');
    }
}
