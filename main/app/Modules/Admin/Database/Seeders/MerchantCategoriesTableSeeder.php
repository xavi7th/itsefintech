<?php
namespace App\Modules\Admin\Database\Seeders;

use Illuminate\Database\Seeder;

class MerchantCategoriesTableSeeder extends Seeder
{

	/**
	 * Auto generated seed file
	 *
	 * @return void
	 */
	public function run()
	{


		\DB::table('merchant_categories')->delete();

		\DB::table('merchant_categories')->insert(array(
			0 =>
			array(
				'id' => 1,
				'name' => 'Eatery',
				'created_at' => NULL,
				'updated_at' => '2020-01-27 21:59:33',
			),
			1 =>
			array(
				'id' => 2,
				'name' => 'Hospitality',
				'created_at' => '2020-01-27 22:00:59',
				'updated_at' => '2020-01-27 22:07:27',
			),
			2 =>
			array(
				'id' => 3,
				'name' => 'Night Life',
				'created_at' => '2020-01-27 22:02:23',
				'updated_at' => '2020-01-27 22:07:39',
			),
			3 =>
			array(
				'id' => 4,
				'name' => 'Clothing Store',
				'created_at' => '2020-01-27 22:07:52',
				'updated_at' => '2020-01-27 22:07:52',
			),
			4 =>
			array(
				'id' => 5,
				'name' => 'Groceries',
				'created_at' => '2020-01-27 22:08:00',
				'updated_at' => '2020-01-27 22:08:00',
			),
			5 =>
			array(
				'id' => 6,
				'name' => 'Travel',
				'created_at' => '2020-01-27 22:08:06',
				'updated_at' => '2020-01-27 22:08:06',
			),
			6 =>
			array(
				'id' => 7,
				'name' => 'Cinema',
				'created_at' => '2020-01-27 22:08:11',
				'updated_at' => '2020-01-27 22:08:11',
			),
			7 =>
			array(
				'id' => 8,
				'name' => 'Utility',
				'created_at' => '2020-01-27 22:08:18',
				'updated_at' => '2020-01-27 22:08:18',
			),
			8 =>
			array(
				'id' => 9,
				'name' => 'Electronics & Gadgets',
				'created_at' => '2020-01-27 22:08:28',
				'updated_at' => '2020-01-27 22:08:55',
			),
			9 =>
			array(
				'id' => 10,
				'name' => 'Education',
				'created_at' => '2020-01-27 22:08:42',
				'updated_at' => '2020-01-27 22:08:42',
			),
			10 =>
			array(
				'id' => 11,
				'name' => 'Others',
				'created_at' => '2020-01-27 22:08:46',
				'updated_at' => '2020-01-27 22:08:46',
			),
		));
	}
}
