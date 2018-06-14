<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateManufacturerYourOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('manufacturer_your_orders', function (Blueprint $table) {
            $table->increments('id');
			$table->unsignedInteger('manufacturer_id');
			$table->unsignedTinyInteger('your_order');

			$table->foreign('manufacturer_id')->references('id')->on('manufacturers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('manufacturer_your_orders');
    }
}
