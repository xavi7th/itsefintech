<?php

namespace App\Modules\Admin\Database\Seeders;

use Illuminate\Database\Seeder;
use App\Modules\Admin\Models\Admin;
use App\Modules\Admin\Models\ApiRoute;
use Illuminate\Database\Eloquent\Model;

class AdminDatabaseSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Model::unguard();

		$this->call(ApiRoutesTableSeeder::class);
		$this->call(AdminsTableSeeder::class);
		$this->call(MerchantCategoriesTableSeeder::class);
		$this->call(CardUserCategoriesTableSeeder::class);
		$this->call(ApiRoutesPermissionsTableSeeder::class);
		$this->call(DebitCardTypesTableSeeder::class);
		$this->call(DebitCardsTableSeeder::class);

		#iseed_start


		#iseed_end
	}
}


class AdminsTableSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		factory(Admin::class, 1)->create();
	}
}
