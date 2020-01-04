<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLoanTransactionsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('loan_transactions', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->bigInteger('card_user_id')->unsigned();
			$table->foreign('card_user_id')->references('id')->on('card_users')->onDelete('cascade');
			$table->bigInteger('loan_request_id')->unsigned();
			$table->foreign('loan_request_id')->references('id')->on('loan_requests')->onDelete('cascade');
			$table->double('amount');
			$table->string('transaction_type')->enum(['loan', 'repayment']);
			// $table->boolean('is_confirmed')->default(false);
			// $table->bigInteger('confirmed_by')->unsigned()->default(null);
			// $table->foreign('confirmed_by')->references('id')->on('admins')->onDelete('no action');
			$table->timestamp('next_installment_due_date');

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
		Schema::dropIfExists('loan_transactions');
	}
}
