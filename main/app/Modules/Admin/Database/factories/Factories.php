<?php

use Faker\Generator as Faker;
use App\Modules\Admin\Models\Admin;
use App\Modules\Admin\Models\ApiRoute;


$factory->define(Admin::class, function (Faker $faker) {

	return [
		'role_id' => 2,
		'full_name' => 'Grant Aghedo',
		'email' => 'grant@amju.com',
		'password' => bcrypt('pass'),
		'phone' => '08034444444444',
		'bvn' => '2567890-98765432',
		'user_passport' => '/storage/' . $faker->file(public_path('img/team/'), storage_path('app/public/admins/'), false),
		'gender' => 'male',
		'address' => '211 56789ygfhbffgh876545c 97564y',
		'dob' => now()->subYears(45),
		'verified_at' => now()->subDays(45),
	];
});
