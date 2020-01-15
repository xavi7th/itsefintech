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
			$table->double('monthly_interest');
			$table->integer('total_duration');
			$table->timestamp('approved_at')->nullable();
			$table->bigInteger('approved_by')->unsigned()->nullable()->default(null);
			$table->foreign('approved_by')->references('id')->on('admins')->onDelete('no action');
			// $table->string('approved_by_user_type')->nullable();
			$table->timestamp('paid_at')->nullable();
			$table->bigInteger('marked_paid_by')->unsigned()->nullable()->default(null);
			$table->foreign('marked_paid_by')->references('id')->on('admins')->onDelete('no action');
			// $table->string('marked_paid_by_user_type')->nullable();

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
