<?php

use App\Models\JobProcessStatus;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJobProcessStatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(JobProcessStatus::TABLE_NAME, static function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->nullable('false');
            $table->softDeletes();
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
        Schema::dropIfExists(JobProcessStatus::TABLE_NAME);
    }
}
