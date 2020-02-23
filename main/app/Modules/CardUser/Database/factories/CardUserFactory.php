<?php


use Faker\Generator as Faker;
use App\Modules\CardUser\Models\CardUser;



/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(CardUser::class, function (Faker $faker) {
	return [
		'first_name' => $faker->firstName,
		'last_name' => $faker->lastName,
		'email' => $faker->unique()->safeEmail,
		'card_user_category_id' => 1,
		'otp_verified_at' => now(),
		'password' => 'pass',
		'phone' => $faker->phoneNumber,
		'user_passport' => '/storage/id_cards/' . $faker->file(public_path('img/'), public_path('storage/card_users/'), false),
		'remember_token' => Str::random(10),
	];
});
