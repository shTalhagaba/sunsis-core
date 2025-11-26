<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTaskColumnsToUserEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_events', function (Blueprint $table) {
            $table->unsignedBigInteger('assign_iqa_id')->nullable()->after('user_id');
            $table->enum('type', ['event', 'task'])->default('event')->after('end');
            $table->enum('task_type', ['rag_rating', 'deep_dive', 'otla', '4_week_audit', 'iqa_sample_plan'])->nullable()->after('event_status');
            $table->tinyInteger('task_status')->nullable()->unsigned()->after('task_type');
            $table->dateTime('completed_at')->nullable()->after('task_status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_events', function (Blueprint $table) {
            $table->dropColumn(['assign_iqa_id', 'type', 'task_type', 'task_status', 'completed_at']);
        });
    }
}
