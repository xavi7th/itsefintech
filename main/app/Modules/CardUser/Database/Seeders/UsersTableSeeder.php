<?php

namespace App\Modules\CardUser\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Modules\CardUser\Models\CardUser;


class UsersTableSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		// DB::table('users')->insert([
		// 	'name' => 'Mitchell Howell Christensen',
		// 	'email' => 'itse@admin.com',
		// 	'password' => bcrypt('pass'),
		// 	'verified_at' => '2019-04-26 07:56:11',
		// 	'country' => 'Canada',
		// 	'phone' => '378809875463456',
		// 	'currency' => 'JPY'
		// ]);

		factory(CardUser::class, 5)->create()->each(function ($user) {
			// $user->transactions()->save(factory(Transaction::class)->make());
			// $user->transactions()->save(factory(Transaction::class)->make());
			// $user->transactions()->save(factory(Transaction::class)->make());
			// $user->transactions()->save(factory(Transaction::class)->make());
			// $user->transactions()->save(factory(Transaction::class)->make());
		});
	}
}
