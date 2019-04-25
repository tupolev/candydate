<?php

use App\JobProcessLog;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJobProcessLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(JobProcessLog::TABLE_NAME, static function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('job_process_id')->nullable(false);
            $table->unsignedBigInteger('job_process_status_id')->nullable(false);
            $table->string('title')->nullable(false);
            $table->text('details')->nullable(false);
            $table->timestamps();
            $table->foreign('job_process_id')->references('id')->on('job_processes');
            $table->foreign('job_process_status_id')->references('id')->on('job_process_statuses');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(JobProcessLog::TABLE_NAME);
    }
}
