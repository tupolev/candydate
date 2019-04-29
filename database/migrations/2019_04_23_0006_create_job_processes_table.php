<?php

use App\Models\JobProcess;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJobProcessesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(JobProcess::TABLE_NAME, static function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->nullable(false);
            $table->string('name')->nullable(false);
            $table->boolean('active')->nullable();
            $table->string('organization_name')->nullable(false);
            $table->text('organization_description')->nullable();
            $table->string('job_title')->nullable(false);
            $table->text('job_description')->nullable();
            $table->string('job_link')->nullable();
            $table->string('job_origin_platform')->nullable();
            $table->string('salary_requested')->nullable();
            $table->string('salary_offered')->nullable();
            $table->string('location_country')->nullable(false);
            $table->string('location_city')->nullable();
            $table->text('location_extra_info')->nullable();
            $table->boolean('is_fully_remote')->nullable();
            $table->date('date_start_offered')->nullable();
            $table->date('date_start_requested')->nullable();
            $table->softDeletes();
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(JobProcess::TABLE_NAME);
    }
}
