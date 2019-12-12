<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDebitCardsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('debit_cards', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->bigInteger('card_user_id')->unsigned();
			$table->foreign('card_user_id')->references('id')->on('card_users')->onDelete('cascade');
			$table->string('card_number')->unique();
			$table->integer('csc');
			$table->integer('month');
			$table->integer('year');
			$table->boolean('is_user_activated')->default(false);
			$table->boolean('is_admin_activated')->default(false);
			$table->boolean('is_suspended')->default(false);

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
		Schema::dropIfExists('debit_cards');
	}
}
