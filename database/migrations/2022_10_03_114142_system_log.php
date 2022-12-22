<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SystemLog extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('system_logs', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->integer('action_id')->nullable();
            $table->integer('action_to_id')->nullable();
            $table->integer('call_type')->nullable();
            $table->integer('call_status')->nullable();
            $table->enum('module', [
                'user',
                'client',
                'city',
                'country',
                'industry',
                'softcall',
                'import',
                'mom',
                'notes',
                'incoming_dashboard',
                'outgoing_dashboard',
                'assign_leads',
                'view_assinged_leads',
                'client_jobs',
                'mailer'
            ])->nullable();
            $table->enum('action_type', [
                'view',
                'create',
                'update',
                'delete',
                'import',
                'export',
                'assign',
                'transfer',
                'call_status',
                'job_status',
                'send_mail'
            ])->nullable();
            $table->text('description')->nullable();
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
        Schema::dropIfExists('system_log');
    }
}
