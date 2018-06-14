<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateManufacturerWeeksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('manufacturer_weeks', function (Blueprint $table) {
			$table->increments('id');
			$table->unsignedInteger('manufacturer_id');
			$table->unsignedSmallInteger('incoming');
			$table->unsignedSmallInteger('available');
			$table->unsignedSmallInteger('new_order');
			$table->unsignedSmallInteger('to_ship');
			$table->unsignedSmallInteger('delivery');
			$table->unsignedSmallInteger('back_order');
			$table->unsignedSmallInteger('inventory');
			$table->unsignedSmallInteger('your_order');
			$table->decimal('cost');

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
        Schema::dropIfExists('manufacturer_weeks');
    }
}
