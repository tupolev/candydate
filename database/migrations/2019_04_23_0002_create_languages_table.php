<?php

use App\Models\Language;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLanguagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(Language::TABLE_NAME, static function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->boolean('active')->nullable();
            $table->string('name')->nullable(false);
            $table->string('locale')->nullable(false);
            $table->timestamps();
            $table->softDeletes();
            $table->unique('name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(Language::TABLE_NAME);
    }
}
