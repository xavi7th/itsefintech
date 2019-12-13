<?php

namespace App\Modules\CardUser\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class CardUserDatabaseSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Model::unguard();

		$this->call(UsersTableSeeder::class);
		$this->call(DebitCardRequestStatusesTableSeeder::class);
	}
}
