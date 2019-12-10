<?php

namespace App\Modules\Accountant\Database\Seeders;

use Illuminate\Database\Seeder;
use App\Modules\Accountant\Models\Accountant;

class AccountantDatabaseSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		$this->call(AccountantTableSeeder::class);
	}
}


class AccountantTableSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		factory(Accountant::class, 1)->create();
	}
}
