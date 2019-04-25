<?php

use App\JobProcess;
use App\JobProcessContact;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJobProcessContactsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(JobProcessContact::TABLE_NAME, static function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('job_process_id')->nullable(false);
            $table->string('fullname')->nullable(false);
            $table->string('email')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('extra_info')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('job_process_id')->references('id')->on(JobProcess::TABLE_NAME);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(JobProcessContact::TABLE_NAME);
    }
}
