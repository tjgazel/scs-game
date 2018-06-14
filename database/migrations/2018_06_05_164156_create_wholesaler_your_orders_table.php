<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWholesalerYourOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wholesaler_your_orders', function (Blueprint $table) {
            $table->increments('id');
			$table->unsignedInteger('wholesaler_id');
			$table->unsignedTinyInteger('your_order');

			$table->foreign('wholesaler_id')->references('id')->on('wholesalers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wholesaler_your_orders');
    }
}
