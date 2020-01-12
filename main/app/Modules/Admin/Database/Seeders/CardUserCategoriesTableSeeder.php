<?php
namespace App\Modules\Admin\Database\Seeders;

use Illuminate\Database\Seeder;

class CardUserCategoriesTableSeeder extends Seeder
{

	/**
	 * Auto generated seed file
	 *
	 * @return void
	 */
	public function run()
	{


		\DB::table('card_user_categories')->delete();

		\DB::table('card_user_categories')->insert(array(
			0 =>
			array(
				'id' => 1,
				'category_name' => 'others',
				'credit_limit' => 0.0,
				'created_at' => NULL,
				'updated_at' => NULL,
				'last_updated_by' => 1,
			),
			1 =>
			array(
				'id' => 2,
				'category_name' => 'students',
				'credit_limit' => 0.0,
				'created_at' => '2020-01-02 17:23:15',
				'updated_at' => '2020-01-02 17:23:15',
				'last_updated_by' => 1,
			),
			2 =>
			array(
				'id' => 3,
				'category_name' => 'salary earners',
				'credit_limit' => 0.0,
				'created_at' => NULL,
				'updated_at' => NULL,
				'last_updated_by' => 1,
			),
			3 =>
			array(
				'id' => 4,
				'category_name' => 'business owners',
				'credit_limit' => 0.0,
				'created_at' => NULL,
				'updated_at' => NULL,
				'last_updated_by' => 1,
			),
		));
	}
}
