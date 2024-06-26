<?php

use App\User;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCardUsersTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('card_users', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->bigInteger('card_user_category_id')->unsigned()->nullable();
			$table->foreign('card_user_category_id')->references('id')->on('card_user_categories')->onDelete('cascade');
			$table->string('first_name')->nullable();
			$table->string('last_name')->nullable();
			$table->string('email')->unique();
			$table->timestamp('otp_verified_at')->nullable();
			$table->string('password')->nullable();
			$table->string('phone')->nullable();
			$table->string('address')->default('not supplied');
			$table->string('city')->default('not supplied');
			$table->string('school')->nullable();
			$table->string('department')->nullable();
			$table->string('level')->nullable();
			$table->string('mat_no')->nullable();
			$table->string('user_passport')->nullable();
			$table->string('bvn')->nullable();
			$table->double('merchant_limit')->nullable();
			$table->double('merchant_percentage')->nullable();
			$table->double('credit_limit')->nullable();
			$table->double('credit_percentage')->nullable();
			// $table->boolean('can_withdraw')->default(false);
			$table->boolean('is_active')->default(true);

			$table->rememberToken();
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
		Schema::dropIfExists('card_users');
	}
}
