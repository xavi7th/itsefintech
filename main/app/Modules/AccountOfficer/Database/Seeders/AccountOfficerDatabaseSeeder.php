<?php

namespace App\Modules\AccountOfficer\Database\Seeders;

use Illuminate\Database\Seeder;
use App\Modules\AccountOfficer\Models\AccountOfficer;

class AccountOfficerDatabaseSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		$this->call(AccountOfficerTableSeeder::class);
	}
}


class AccountOfficerTableSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		factory(AccountOfficer::class, 1)->create();
	}
}
