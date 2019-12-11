<?php

namespace App\Modules\DispatchAdmin\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\Modules\DispatchAdmin\Models\DispatchAdmin;

class DispatchAdminDatabaseSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		$this->call(DispatchAdminTableSeeder::class);
	}
}


class DispatchAdminTableSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		factory(DispatchAdmin::class, 1)->create();
	}
}
