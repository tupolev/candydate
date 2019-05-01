<?php

use App\Models\User;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(User::TABLE_NAME, static function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->boolean('active')->nullable(false)->default(true);
            $table->string('fullname')->nullable();
            $table->string('username', 128)->nullable(false);
            $table->string('password')->nullable(false);
            $table->string('email', 256)->nullable(false);
            $table->string('salt')->nullable(false);
            $table->boolean('verified')->nullable(false)->default(false);
            $table->string('verification_link')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->unsignedBigInteger('language_id')->nullable(false);
            $table->unsignedBigInteger('role_id')->nullable(false);
            $table->foreign('language_id')->references('id')->on('languages');
            $table->foreign('role_id')->references('id')->on('roles');
            $table->unique('username');
            $table->unique('email');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(User::TABLE_NAME);
    }
}
