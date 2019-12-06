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

$factory->define(User::class, function (Faker $faker) {
	return [
		'name' => $faker->name,
		'email' => $faker->unique()->safeEmail,
		'email_verified_at' => now(),
		'password' => 'pass',
		'country' => $faker->country,
		'phone' => $faker->phoneNumber,
		'currency' => $faker->currencyCode,
		'id_card' => '/storage/id_cards/' . $faker->file(public_path(''), public_path('storage/card_users/'), false),
		'remember_token' => Str::random(10),
	];
});
