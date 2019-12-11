<?php

namespace App\Modules\SalesRep\Database\Seeders;

use Illuminate\Database\Seeder;
use App\Modules\SalesRep\Models\SalesRep;

class SalesRepDatabaseSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		$this->call(SalesRepTableSeeder::class);
	}
}


class SalesRepTableSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		factory(SalesRep::class, 1)->create();
	}
}
