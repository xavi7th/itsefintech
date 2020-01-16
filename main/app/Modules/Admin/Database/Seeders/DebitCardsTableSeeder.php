<?php
namespace App\Modules\Admin\Database\Seeders;

use Illuminate\Database\Seeder;

class DebitCardsTableSeeder extends Seeder
{

	/**
	 * Auto generated seed file
	 *
	 * @return void
	 */
	public function run()
	{


		\DB::table('debit_cards')->delete();

		\DB::table('debit_cards')->insert(array(
			0 =>
			array(
				'id' => 1,
				'sales_rep_id' => NULL,
				'card_user_id' => NULL,
				'debit_card_type_id' => 1,
				'card_number' => 'eyJpdiI6IkY3a3FONUNaV2tvNlJLdVdjMkhXbWc9PSIsInZhbHVlIjoiS0tLbDhjSFMzVXJaMUY3eVJiek0xMEZXWWp5WStnVWViaXFONEVCaEtSRT0iLCJtYWMiOiI3NTQ3Nzg1ZmJjYmExNmM1NDZkN2JkOTgxNGUwMGZjM2M3ZDIzYjFmODYwMmViMzE2ZDljNzNjN2Y3ZGVjNzFiIn0=',
				'card_hash' => '58f1f258a5b80dfc2ea7035ac16f2427ff43f5a6fba4994a6cbaed6ef7bda3e7ad93d6eead23f55866fdbc4bae1ce5322e926e33a2cf5c43b3ec6dcdd0e3391b',
				'csc' => '$2y$10$PQB7nw2mo/QC4IMn9lRk3ek/bznfqcgSlgG9/akJ0J/AXcbKnY7He',
				'month' => 11,
				'year' => 2085,
				'is_user_activated' => 0,
				'is_admin_activated' => 0,
				'activated_at' => NULL,
				'is_suspended' => 0,
				'assigned_by' => NULL,
				'created_by' => NULL,
				'created_at' => '2020-01-16 04:55:56',
				'updated_at' => '2020-01-16 04:55:56',
				'deleted_at' => NULL,
			),
			1 =>
			array(
				'id' => 2,
				'sales_rep_id' => NULL,
				'card_user_id' => NULL,
				'debit_card_type_id' => 1,
				'card_number' => 'eyJpdiI6ImFDdDh5eUtJbUVDdFZJS2NpZHpZTlE9PSIsInZhbHVlIjoidmZLaURMY3RycnIzZHVDVUhWQ3VoUllhYVhYeTB5REh6Q3hWY2J5UDlJcz0iLCJtYWMiOiI4ZTRmOWMzYjk5Y2I0NmU0MzVhNDA4MTNhYTZjZjM3YTQ3NDZkZTFmMDcwYjM5MTc5NDllMmIzNDg5NDJlOTQ5In0=',
				'card_hash' => '2159e98da7c4145cf7df1a785c5c079e30639780d4719e4d3345dfed2e37799cf53f5a8916b8066d3cb3e253fcf8e687de20790b3059b9f4a828f8c98ef16493',
				'csc' => '$2y$10$Zxdf6qfEZSWKBWhbSt2HnedHmRwcnNZhYmnwzPa065Mak3OdtgdrC',
				'month' => 10,
				'year' => 2069,
				'is_user_activated' => 0,
				'is_admin_activated' => 0,
				'activated_at' => NULL,
				'is_suspended' => 0,
				'assigned_by' => NULL,
				'created_by' => NULL,
				'created_at' => '2020-01-16 04:56:14',
				'updated_at' => '2020-01-16 04:56:14',
				'deleted_at' => NULL,
			),
			2 =>
			array(
				'id' => 3,
				'sales_rep_id' => NULL,
				'card_user_id' => NULL,
				'debit_card_type_id' => 1,
				'card_number' => 'eyJpdiI6IlVyWG5CM2hDTU9Fb1V5Q0F6dlJqSWc9PSIsInZhbHVlIjoiK3RsdzZ0bDZveXloZ0pRUjJscnNDMktvUlJwZjEyNzczXC9QYU9ZaDZObU09IiwibWFjIjoiOTY2NmFmZThkNjAzNzkxNWIxOWM2Y2QzNDc0ZDM1ZWViZmQ4NDE5YzdhNThhNzQyMTAwOTU2NjlmNDlkNzc1NiJ9',
				'card_hash' => '557c9e3c5521f01dacb0a116d9d715d2f3bb7b4df2280b239d9a3d3e7e63aa931f1973b1ec7d5f786ca38dc23e56a74eb9cba9decc00136c09b2b3171b939110',
				'csc' => '$2y$10$0ZefDZtBUgqaoVExqpkfzOS3FLEzoZF24vHjCM3hRApswLINK602.',
				'month' => 1,
				'year' => 2086,
				'is_user_activated' => 0,
				'is_admin_activated' => 0,
				'activated_at' => NULL,
				'is_suspended' => 0,
				'assigned_by' => NULL,
				'created_by' => NULL,
				'created_at' => '2020-01-16 04:56:44',
				'updated_at' => '2020-01-16 04:56:44',
				'deleted_at' => NULL,
			),
			3 =>
			array(
				'id' => 4,
				'sales_rep_id' => NULL,
				'card_user_id' => NULL,
				'debit_card_type_id' => 1,
				'card_number' => 'eyJpdiI6IjJSdFVSbVkyVmlcL1wvUkxqU01kNVIxQT09IiwidmFsdWUiOiJUMkE1TGo5MTU4cVBFZWU0K1ZcL1ZPUlRlYWtGU0hCZ2VNWHV0a3VucmpDQT0iLCJtYWMiOiIxYTYzMmYwOTdmMWU5Njk0OWZhMmU2NmZmMjA5YTVhZGY1ZDljNGVhN2NjMDllNmEyOWViMmU2NjY0YTg2MWRlIn0=',
				'card_hash' => '0a4c09bb0d62144d9c5c3d299a1f4d8fad11e603dac738cffb7015fe4b141029a16bae7338384f60a1069e7b7804322d7a791ed03cfb6ca7bc57b165f21af322',
				'csc' => '$2y$10$l/casTZO0t3Tb0W/tnBD.eQnpcKYC1gf9JhDs9e.Qzo0kvJiLHN/e',
				'month' => 4,
				'year' => 2022,
				'is_user_activated' => 0,
				'is_admin_activated' => 0,
				'activated_at' => NULL,
				'is_suspended' => 0,
				'assigned_by' => NULL,
				'created_by' => NULL,
				'created_at' => '2020-01-16 04:56:59',
				'updated_at' => '2020-01-16 04:56:59',
				'deleted_at' => NULL,
			),
			4 =>
			array(
				'id' => 5,
				'sales_rep_id' => NULL,
				'card_user_id' => NULL,
				'debit_card_type_id' => 2,
				'card_number' => 'eyJpdiI6InUzRnBaQUdhMm9ocUwwdTB1STZxM1E9PSIsInZhbHVlIjoiNXlrXC9TMzJqeW9RWVdFM0FQODl3TjVHNFduU09GeHZWcStWYTNTRGhuWDg9IiwibWFjIjoiZmJmZjBmZWYzMGExM2UyZjE0MmNkYjQxZGVjZDMzMGZmYTMwMTBkYWQ5ZGFmY2NjZGMwNGM5MWYyNDI2YTkzMSJ9',
				'card_hash' => '47bf3d748b2518a9cb1cd26ad89ac42729536f4fcbc99a31f4963530564475543c6835ce627b9008ba55bbc35e76f097e979311e4f3a09319ea7cde0e5fc1dfd',
				'csc' => '$2y$10$sUh7Uiq6tICoK6k3ctgnnuW8185xF0206yqEMB0lirnW1dkpgQ6rG',
				'month' => 9,
				'year' => 2027,
				'is_user_activated' => 0,
				'is_admin_activated' => 0,
				'activated_at' => NULL,
				'is_suspended' => 0,
				'assigned_by' => NULL,
				'created_by' => NULL,
				'created_at' => '2020-01-16 04:57:11',
				'updated_at' => '2020-01-16 04:57:11',
				'deleted_at' => NULL,
			),
			5 =>
			array(
				'id' => 6,
				'sales_rep_id' => NULL,
				'card_user_id' => NULL,
				'debit_card_type_id' => 2,
				'card_number' => 'eyJpdiI6InNDajVSRW9mSElRVDlMNmVaMkFFb1E9PSIsInZhbHVlIjoiUUdHSUo3a0NXazVaa3hUWUQ5TmdLR3N6MVwvV0VYRGcyRjBhQ0R5RXcxOVU9IiwibWFjIjoiYTM3NThiMGM1ZjQxOWNjYWIyMGU0YWYxOTM5YTI4YzlhMWYxMzhhMGY2ZjZmN2E2NmM1MDJmYWU4ZTRhOTA2NyJ9',
				'card_hash' => 'f6395b69bcf19aaab34c23c0ac1cf789c355351bd0f62659e3d5876643110b7a14bee4e7bae0bb381db2980091d1afa2f49edad8bb1f45119c570059633cbb05',
				'csc' => '$2y$10$shaj1aS/NUgfJU/Y3/.4HuXr2Mw7asPwsjCzhcCCc63gjCC/AEkRS',
				'month' => 6,
				'year' => 2052,
				'is_user_activated' => 0,
				'is_admin_activated' => 0,
				'activated_at' => NULL,
				'is_suspended' => 0,
				'assigned_by' => NULL,
				'created_by' => NULL,
				'created_at' => '2020-01-16 04:57:22',
				'updated_at' => '2020-01-16 04:57:22',
				'deleted_at' => NULL,
			),
		));
	}
}
