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
		'name' => $faker->name,
		'email' => $faker->unique()->safeEmail,
		'otp_verified_at' => now(),
		'password' => 'pass',
		'phone' => $faker->phoneNumber,
		'user_passport' => '/storage/id_cards/' . $faker->file(public_path(''), public_path('storage/card_users/'), false),
		'remember_token' => Str::random(10),
	];
});
