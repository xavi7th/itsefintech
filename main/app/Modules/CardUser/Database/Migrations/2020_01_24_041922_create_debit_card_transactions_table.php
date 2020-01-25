<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDebitCardTransactionsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('debit_card_transactions', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->bigInteger('card_user_id')->unsigned();
			$table->foreign('card_user_id')->references('id')->on('card_users')->onDelete('cascade');
			$table->bigInteger('debit_card_id')->unsigned();
			$table->foreign('debit_card_id')->references('id')->on('debit_cards')->onDelete('cascade');
			$table->double('amount');
			$table->string('trans_description');
			$table->string('trans_category');
			$table->string('paystack_id')->nullable();
			$table->string('paystack_ref')->nullable();
			$table->string('paystack_message')->nullable();
			$table->string('quickteller_req_ref')->nullable();
			$table->string('quickteller_trans_ref')->nullable();
			$table->string('quickteller_res_code')->nullable();
			$table->enum('trans_type', ['debit', 'credit'])->default('debit');
			$table->boolean('is_unresolved')->default(false);

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
		Schema::dropIfExists('debit_card_transactions');
	}
}
