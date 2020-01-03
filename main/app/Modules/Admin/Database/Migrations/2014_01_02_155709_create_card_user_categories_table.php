<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCardUserCategoriesTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('card_user_categories', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->string('category_name');
			$table->double('credit_limit')->default(0);

			$table->timestamps();
			$table->bigInteger('last_updated_by')->unsigned()->nullable();
			$table->foreign('last_updated_by')->references('id')->on('admins')->onDelete('no action');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('card_user_categories');
	}
}
