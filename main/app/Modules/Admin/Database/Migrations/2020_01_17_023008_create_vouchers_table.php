<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVouchersTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('vouchers', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->string('card_user_id');
			$table->foreign('card_user_id')->references('id')->on('card_users')->onDelete('cascade');
			$table->string('code')->unique();
			$table->double('amount');

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
		Schema::dropIfExists('vouchers');
	}
}
