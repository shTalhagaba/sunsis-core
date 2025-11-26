<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTrTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tr_tasks', function (Blueprint $table) {
            if (!Schema::hasColumn('tr_tasks', 'pro_task_id')) {
                $table->unsignedBigInteger('pro_task_id')->nullable()->after('dp_session_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
