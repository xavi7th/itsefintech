<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDebitCardRequestsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('debit_card_requests', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->bigInteger('card_user_id')->unsigned();
			$table->foreign('card_user_id')->references('id')->on('card_users')->onDelete('cascade');
			$table->bigInteger('debit_card_request_status_id')->unsigned()->default(1);
			$table->foreign('debit_card_request_status_id')->references('id')->on('debit_card_request_statuses')->onDelete('cascade')->onUpdate('cascade');
			$table->string('phone');
			$table->string('address');
			$table->string('zip');
			$table->string('city');
			$table->string('payment_method');
			$table->boolean('is_paid')->default(false);

			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('debit_card_requests');
	}
}
