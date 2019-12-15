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
			$table->bigInteger('debit_card_id')->unsigned()->nullable();
			$table->foreign('debit_card_id')->references('id')->on('debit_cards')->onDelete('cascade');
			$table->bigInteger('card_user_id')->unsigned();
			$table->foreign('card_user_id')->references('id')->on('card_users')->onDelete('cascade');
			$table->bigInteger('sales_rep_id')->unsigned()->nullable();
			$table->foreign('sales_rep_id')->references('id')->on('sales_reps')->onDelete('cascade');
			$table->bigInteger('debit_card_request_status_id')->unsigned()->default(1);
			$table->foreign('debit_card_request_status_id')->references('id')->on('debit_card_request_statuses')->onDelete('cascade')->onUpdate('cascade');
			$table->string('phone');
			$table->string('address');
			$table->string('zip');
			$table->string('city');
			$table->string('payment_method');
			$table->boolean('is_paid')->default(false);
			$table->boolean('is_payment_confirmed')->default(false);
			$table->bigInteger('confirmed_by')->unsigned()->nullable();
			$table->foreign('confirmed_by')->references('id')->on('accountants')->onDelete('cascade');
			// $table->string('confirmation_user_type')->nullable();

			$table->bigInteger('last_updated_by')->unsigned()->nullable();
			$table->string('last_updater_user_type')->nullable();
			$table->timestamps();
			$table->softDeletes()();
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
