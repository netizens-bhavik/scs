<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMomsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('moms', function (Blueprint $table) {
            $table->id();
            $table->integer('client_id')->nullable();
            $table->date('meeting_date')->nullable();
           // $table->string('company_name')->nullable();
            $table->string('contact_person')->nullable();
            $table->text('minutes_of_meeting')->nullable();
           // $table->integer('mode_of_meeting')->nullable();
            $table->text('bde_feedback')->nullable();
            $table->enum('mom_type', ['1', '2', '3', '4'])->comment('1-followUp, 2-meeting, 3-requirementDiscussion, 4-close')->nullable();
            $table->enum('followup', ['1', '2', '3'])->comment('1-personal, 2-share, 3-next followup datetime')->nullable();
            $table->integer('share_user_id')->nullable();
            $table->integer('shared_user_by')->nullable();
            $table->tinyInteger('is_shared_user_notified')->nullable()->default(0);
            $table->date('next_followup_date')->nullable();
            $table->time('next_followup_time')->nullable();
            $table->integer('followup_status')->nullable();
            $table->enum('client_status', ['1', '2', '3','4','5','6'])
                ->comment('1-high priority, 2-medium priority, 3-low priority, 4-requirement received, 5-under discussion, 6-Closed')
                ->nullable();
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
        Schema::dropIfExists('moms');
    }
}
