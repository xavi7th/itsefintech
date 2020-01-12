<?php
namespace App\Modules\CardUser\Database\Seeders;

use Illuminate\Database\Seeder;

class DebitCardRequestStatusesTableSeeder extends Seeder
{

	/**
	 * Auto generated seed file
	 *
	 * @return void
	 */
	public function run()
	{


		\DB::table('debit_card_request_statuses')->delete();

		\DB::table('debit_card_request_statuses')->insert(array(
			0 =>
			array(
				'id' => 1,
				'name' => 'Card requested',
			),
			1 =>
			array(
				'id' => 2,
				'name' => 'Processing',
			),
			2 =>
			array(
				'id' => 3,
				'name' => 'Ready for dispatch',
			),
			3 =>
			array(
				'id' => 4,
				'name' => 'In transit',
			),
			4 =>
			array(
				'id' => 5,
				'name' => 'Enroute to customer',
			),
			5 =>
			array(
				'id' => 6,
				'name' => 'Delivered to customer',
			)
		));
	}
}
