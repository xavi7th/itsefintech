<?php

namespace App\Modules\CustomerSupport\Database\Seeders;

use Illuminate\Database\Seeder;
use App\Modules\CustomerSupport\Models\CustomerSupport;


class CustomerSupportDatabaseSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		$this->call(CustomerSupportTableSeeder::class);
	}
}


class CustomerSupportTableSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		factory(CustomerSupport::class, 1)->create();
	}
}
