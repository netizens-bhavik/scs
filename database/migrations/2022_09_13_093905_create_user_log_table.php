<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::create('system_log', function (Blueprint $table) {
        //     $table->id();
        //     $table->integer('user_id');
        //     $table->integer('client_id')->nullable();
        //     $table->integer('lead_id')->nullable();
        //     $table->integer('mom_id')->nullable();
        //     $table->enum('type', ['login', 'logout', 'create', 'update', 'delete', 'import', 'assign', 'transfer', 'callbacks', 'call_status'])->nullable();
        //     $table->text('description')->nullable();
        //     $table->timestamps();
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //Schema::dropIfExists('system_log');
    }
}
