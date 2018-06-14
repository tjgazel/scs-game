<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDistributorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('distributors', function (Blueprint $table) {
            $table->increments('id');
			$table->unsignedInteger('game_id');
			$table->unsignedInteger('manufacturer_id');
			$table->unsignedInteger('user_id')->nullable();
			$table->boolean('autoplayer')->default(true);

			$table->foreign('game_id')
				->references('id')
				->on('games')
				->onDelete('cascade');
			$table->foreign('manufacturer_id')
				->references('id')
				->on('manufacturers')
				->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('distributors');
    }
}
