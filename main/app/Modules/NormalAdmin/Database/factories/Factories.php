<?php

use Faker\Generator as Faker;
use App\Modules\NormalAdmin\Models\NormalAdmin;


$factory->define(NormalAdmin::class, function (Faker $faker) {

	return [
		'full_name' => 'Grant Normal Aghedo',
		'email' => 'add@itsefintech.com',
		'password' => bcrypt('pass'),
		'phone' => '08034444444444',
		'bvn' => '2567890-98765432',
		'user_passport' => '/storage/' . $faker->file(public_path('img/'), storage_path('app/public/admins/'), false),
		'gender' => 'male',
		'address' => '211 56789ygfhbffgh876545c 97564y',
		'dob' => now()->subYears(45),
		'verified_at' => now()->subDays(45),
	];
});
