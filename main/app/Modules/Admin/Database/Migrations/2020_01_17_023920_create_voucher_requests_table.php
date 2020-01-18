<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVoucherRequestsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('voucher_requests', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->bigInteger('voucher_id')->unsigned()->nullable();
			$table->foreign('voucher_id')->references('id')->on('vouchers')->onDelete('cascade');

			$table->bigInteger('card_user_id')->unsigned();
			$table->foreign('card_user_id')->references('id')->on('card_users')->onDelete('cascade');
			$table->double('amount');
			$table->timestamp('approved_at')->nullable();
			$table->bigInteger('approved_by')->unsigned()->nullable();
			$table->foreign('approved_by')->references('id')->on('admins')->onDelete('cascade');

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
		Schema::dropIfExists('voucher_requests');
	}
}
