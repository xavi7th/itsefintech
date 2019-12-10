<?php

namespace App\Modules\NormalAdmin\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\Modules\NormalAdmin\Models\NormalAdmin;

class NormalAdminDatabaseSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Model::unguard();

		$this->call(NormalAdminsTableSeeder::class);
	}
}


class NormalAdminsTableSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		factory(NormalAdmin::class, 1)->create();
	}
}
