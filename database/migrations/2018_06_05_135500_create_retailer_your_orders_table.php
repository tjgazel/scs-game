<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRetailerYourOrdersTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('retailer_your_orders', function (Blueprint $table) {
			$table->increments('id');
			$table->unsignedInteger('retailer_id');
			$table->unsignedTinyInteger('your_order');

			$table->foreign('retailer_id')->references('id')->on('retailers')->onDelete('cascade');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('retailer_your_orders');
	}
}
