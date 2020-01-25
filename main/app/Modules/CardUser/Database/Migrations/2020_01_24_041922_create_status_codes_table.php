<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStatusCodesTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('status_codes', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->string('vendor_name')->default('PAYSTACK');
			$table->string('code');
			$table->string('status_message')->nullable();
			$table->text('description')->nullable();

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
		Schema::dropIfExists('status_codes');
	}
}
