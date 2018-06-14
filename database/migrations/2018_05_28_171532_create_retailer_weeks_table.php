<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRetailerWeeksTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('retailer_weeks', function (Blueprint $table) {
			$table->increments('id');
			$table->unsignedInteger('retailer_id');
			$table->unsignedSmallInteger('incoming');
			$table->unsignedSmallInteger('available');
			$table->unsignedSmallInteger('new_order');
			$table->unsignedSmallInteger('to_ship');
			$table->unsignedSmallInteger('delivery');
			$table->unsignedSmallInteger('back_order');
			$table->unsignedSmallInteger('inventory');
			$table->unsignedSmallInteger('your_order');
			$table->decimal('cost');

			$table->foreign('retailer_id')
				->references('id')
				->on('retailers')
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
		Schema::dropIfExists('retailer_weeks');
	}
}
