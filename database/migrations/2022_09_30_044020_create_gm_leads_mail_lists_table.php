<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGmLeadsMailListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gm_leads_mail_lists', function (Blueprint $table) {
            $table->id();
            $table->integer('lead_id');
            $table->string('name')->nullable();
            $table->string('country')->nullable();
            $table->boolean('is_mail_sent')->default(false);
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
        Schema::dropIfExists('gm_leads_mail_lists');
    }
}
