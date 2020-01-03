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
			$table->bigInteger('sales_rep_id')->unsigned()->nullable();
			$table->foreign('sales_rep_id')->references('id')->on('sales_reps')->onDelete('cascade');
			$table->bigInteger('card_user_id')->unsigned()->nullable();
			$table->foreign('card_user_id')->references('id')->on('card_users')->onDelete('cascade');
			$table->string('card_number')->unique();
			$table->string('card_hash');
			$table->string('csc');
			$table->integer('month');
			$table->integer('year');
			$table->boolean('is_user_activated')->default(false);
			$table->boolean('is_admin_activated')->default(false);
			$table->timestamp('activated_at')->nullable();
			$table->boolean('is_suspended')->default(false);

			$table->bigInteger('assigned_by')->unsigned()->nullable();
			$table->bigInteger('created_by')->unsigned()->nullable();
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
