<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDistributorWeeksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('distributor_weeks', function (Blueprint $table) {
			$table->increments('id');
			$table->unsignedInteger('distributor_id');
			$table->unsignedSmallInteger('incoming');
			$table->unsignedSmallInteger('available');
			$table->unsignedSmallInteger('new_order');
			$table->unsignedSmallInteger('to_ship');
			$table->unsignedSmallInteger('delivery');
			$table->unsignedSmallInteger('back_order');
			$table->unsignedSmallInteger('inventory');
			$table->unsignedSmallInteger('your_order');
			$table->decimal('cost');

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
        Schema::dropIfExists('distributor_weeks');
    }
}
