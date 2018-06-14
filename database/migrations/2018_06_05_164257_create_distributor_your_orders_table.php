<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDistributorYourOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('distributor_your_orders', function (Blueprint $table) {
            $table->increments('id');
			$table->unsignedInteger('distributor_id');
			$table->unsignedTinyInteger('your_order');

			$table->foreign('distributor_id')->references('id')->on('distributors')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('distributor_your_orders');
    }
}
