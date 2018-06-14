<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWholesalersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wholesalers', function (Blueprint $table) {
            $table->increments('id');
			$table->unsignedInteger('game_id');
			$table->unsignedInteger('distributor_id');
			$table->unsignedInteger('user_id')->nullable();
			$table->boolean('autoplayer')->default(true);

			$table->foreign('game_id')
				->references('id')
				->on('games')
				->onDelete('cascade');
			$table->foreign('distributor_id')
				->references('id')
				->on('distributors')
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
        Schema::dropIfExists('wholesalers');
    }
}
