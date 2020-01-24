<?php
namespace App\Modules\Admin\Database\Seeders;

use Illuminate\Database\Seeder;

class DebitCardTypesTableSeeder extends Seeder
{

	/**
	 * Auto generated seed file
	 *
	 * @return void
	 */
	public function run()
	{


		\DB::table('debit_card_types')->delete();

		\DB::table('debit_card_types')->insert(array(
			0 =>
			array(
				'id' => 1,
				'card_type_name' => 'Titanium Black',
				'amount' => 1000,
				'created_at' => '2020-01-16 04:51:47',
				'updated_at' => '2020-01-16 04:51:47',
				'deleted_at' => NULL,
			),
			1 =>
			array(
				'id' => 2,
				'card_type_name' => 'Titanium Platinum',
				'amount' => 1500,
				'created_at' => '2020-01-16 04:55:06',
				'updated_at' => '2020-01-16 04:55:06',
				'deleted_at' => NULL,
			),
		));
	}
}
