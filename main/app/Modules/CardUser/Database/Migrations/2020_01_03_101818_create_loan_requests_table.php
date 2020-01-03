<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLoanRequestsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('loan_requests', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->bigInteger('card_user_id');
			$table->double('amount');
			$table->integer('total_duration');
			$table->integer('repayment_duration');
			$table->integer('repayment_amount');
			$table->boolean('is_approved')->default(false);
			$table->bigInteger('approved_by')->unsigned()->nullable()->default(null);
			$table->foreign('approved_by')->references('id')->on('admins')->onDelete('no action');
			// $table->string('confirmation_user_type')->nullable();

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
		Schema::dropIfExists('loan_requests');
	}
}
