<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMerchantsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('merchants', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->string('name');
			$table->string('merchant_img');
			$table->bigInteger('merchant_category_id')->unsigned();
			$table->foreign('merchant_category_id')->references('id')->on('merchant_categories')->onDelete('cascade');
			$table->string('address');
			$table->string('email')->nullable();
			$table->string('phone')->nullable();
			$table->string('unique_code')->unique();
			$table->boolean('is_active')->default(true);

			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('merchants');
	}
}
