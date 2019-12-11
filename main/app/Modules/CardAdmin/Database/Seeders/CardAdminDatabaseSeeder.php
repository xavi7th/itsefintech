<?php

namespace App\Modules\CardAdmin\Database\Seeders;

use Illuminate\Database\Seeder;
use App\Modules\CardAdmin\Models\CardAdmin;

class CardAdminDatabaseSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		$this->call(CardAdminTableSeeder::class);
	}
}


class CardAdminTableSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		factory(CardAdmin::class, 1)->create();
	}
}
