<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMerchantTransactionsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('merchant_transactions', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->bigInteger('voucher_id')->unsigned();
			$table->foreign('voucher_id')->references('id')->on('vouchers')->onDelete('cascade');
			$table->bigInteger('card_user_id')->unsigned();
			$table->foreign('card_user_id')->references('id')->on('card_users')->onDelete('cascade');
			$table->bigInteger('merchant_id')->unsigned()->nullable();
			$table->foreign('merchant_id')->references('id')->on('merchants')->onDelete('cascade');
			$table->double('amount');
			$table->enum('trans_type', ['repayment', 'debit', 'debit request']);
			$table->string('description')->nullable();


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
		Schema::dropIfExists('merchant_transactions');
	}
}
