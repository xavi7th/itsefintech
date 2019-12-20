<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStockRequestsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('stock_requests', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->bigInteger('sales_rep_id')->unsigned();
			$table->foreign('sales_rep_id')->references('id')->on('sales_reps')->onDelete('cascade');
			$table->integer('number_of_cards');
			$table->boolean('is_processed')->default(false);

			$table->timestamps();
			$table->softDeletes();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('stock_requests');
	}
}
