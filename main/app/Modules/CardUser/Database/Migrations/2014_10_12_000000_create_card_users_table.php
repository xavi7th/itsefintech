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
			$table->string('first_name')->nullable();
			$table->string('last_name')->nullable();
			$table->string('email')->unique();
			$table->timestamp('otp_verified_at')->nullable();
			$table->string('password')->nullable();
			$table->string('phone')->nullable();
			$table->string('address')->default('not supplied');
			$table->string('city')->default('not supplied');
			$table->string('zip')->default(00000);
			$table->string('user_passport')->nullable();
			$table->string('bvn')->nullable()->nullable();
			$table->boolean('can_withdraw')->default(false);
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
