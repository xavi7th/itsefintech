<?php
namespace App\Modules\Admin\Database\Seeders;

use Illuminate\Database\Seeder;

class ApiRoutesPermissionsTableSeeder extends Seeder
{

	/**
	 * Auto generated seed file
	 *
	 * @return void
	 */
	public function run()
	{


		\DB::table('api_routes_permissions')->delete();

		\DB::table('api_routes_permissions')->insert(array(
			0 =>
			array(
				'id' => 1,
				'user_id' => 1,
				'api_route_id' => 2,
				'user_type' => 'App\\Modules\\Admin\\Models\\Admin',
			),
			1 =>
			array(
				'id' => 2,
				'user_id' => 1,
				'api_route_id' => 1,
				'user_type' => 'App\\Modules\\Admin\\Models\\Admin',
			),
			2 =>
			array(
				'id' => 3,
				'user_id' => 1,
				'api_route_id' => 3,
				'user_type' => 'App\\Modules\\Admin\\Models\\Admin',
			),
			3 =>
			array(
				'id' => 4,
				'user_id' => 1,
				'api_route_id' => 4,
				'user_type' => 'App\\Modules\\Admin\\Models\\Admin',
			),
			4 =>
			array(
				'id' => 5,
				'user_id' => 1,
				'api_route_id' => 5,
				'user_type' => 'App\\Modules\\Admin\\Models\\Admin',
			),
			5 =>
			array(
				'id' => 6,
				'user_id' => 1,
				'api_route_id' => 6,
				'user_type' => 'App\\Modules\\Admin\\Models\\Admin',
			),
			6 =>
			array(
				'id' => 7,
				'user_id' => 1,
				'api_route_id' => 7,
				'user_type' => 'App\\Modules\\Admin\\Models\\Admin',
			),
			7 =>
			array(
				'id' => 8,
				'user_id' => 1,
				'api_route_id' => 8,
				'user_type' => 'App\\Modules\\Admin\\Models\\Admin',
			),
			8 =>
			array(
				'id' => 9,
				'user_id' => 1,
				'api_route_id' => 9,
				'user_type' => 'App\\Modules\\Admin\\Models\\Admin',
			),
			9 =>
			array(
				'id' => 10,
				'user_id' => 1,
				'api_route_id' => 10,
				'user_type' => 'App\\Modules\\Admin\\Models\\Admin',
			),
			10 =>
			array(
				'id' => 11,
				'user_id' => 1,
				'api_route_id' => 11,
				'user_type' => 'App\\Modules\\Admin\\Models\\Admin',
			),
			11 =>
			array(
				'id' => 12,
				'user_id' => 1,
				'api_route_id' => 12,
				'user_type' => 'App\\Modules\\Admin\\Models\\Admin',
			),
			12 =>
			array(
				'id' => 13,
				'user_id' => 1,
				'api_route_id' => 13,
				'user_type' => 'App\\Modules\\Admin\\Models\\Admin',
			),
			13 =>
			array(
				'id' => 14,
				'user_id' => 1,
				'api_route_id' => 14,
				'user_type' => 'App\\Modules\\Admin\\Models\\Admin',
			),
			14 =>
			array(
				'id' => 15,
				'user_id' => 1,
				'api_route_id' => 15,
				'user_type' => 'App\\Modules\\Admin\\Models\\Admin',
			),
			15 =>
			array(
				'id' => 16,
				'user_id' => 1,
				'api_route_id' => 16,
				'user_type' => 'App\\Modules\\Admin\\Models\\Admin',
			),
			16 =>
			array(
				'id' => 17,
				'user_id' => 1,
				'api_route_id' => 17,
				'user_type' => 'App\\Modules\\Admin\\Models\\Admin',
			),
			17 =>
			array(
				'id' => 18,
				'user_id' => 1,
				'api_route_id' => 18,
				'user_type' => 'App\\Modules\\Admin\\Models\\Admin',
			),
			18 =>
			array(
				'id' => 19,
				'user_id' => 1,
				'api_route_id' => 19,
				'user_type' => 'App\\Modules\\Admin\\Models\\Admin',
			),
			19 =>
			array(
				'id' => 20,
				'user_id' => 1,
				'api_route_id' => 20,
				'user_type' => 'App\\Modules\\Admin\\Models\\Admin',
			),
			20 =>
			array(
				'id' => 21,
				'user_id' => 1,
				'api_route_id' => 21,
				'user_type' => 'App\\Modules\\Admin\\Models\\Admin',
			),
			21 =>
			array(
				'id' => 22,
				'user_id' => 1,
				'api_route_id' => 22,
				'user_type' => 'App\\Modules\\Admin\\Models\\Admin',
			),
			22 =>
			array(
				'id' => 23,
				'user_id' => 1,
				'api_route_id' => 23,
				'user_type' => 'App\\Modules\\Admin\\Models\\Admin',
			),
			23 =>
			array(
				'id' => 24,
				'user_id' => 1,
				'api_route_id' => 24,
				'user_type' => 'App\\Modules\\Admin\\Models\\Admin',
			),
			24 =>
			array(
				'id' => 25,
				'user_id' => 1,
				'api_route_id' => 25,
				'user_type' => 'App\\Modules\\Admin\\Models\\Admin',
			),
			25 =>
			array(
				'id' => 26,
				'user_id' => 1,
				'api_route_id' => 26,
				'user_type' => 'App\\Modules\\Admin\\Models\\Admin',
			),
			26 =>
			array(
				'id' => 27,
				'user_id' => 1,
				'api_route_id' => 27,
				'user_type' => 'App\\Modules\\Admin\\Models\\Admin',
			),
			27 =>
			array(
				'id' => 28,
				'user_id' => 1,
				'api_route_id' => 28,
				'user_type' => 'App\\Modules\\Admin\\Models\\Admin',
			),
			28 =>
			array(
				'id' => 29,
				'user_id' => 1,
				'api_route_id' => 16,
				'user_type' => 'App\\Modules\\SalesRep\\Models\\SalesRep',
			),
			29 =>
			array(
				'id' => 30,
				'user_id' => 1,
				'api_route_id' => 5,
				'user_type' => 'App\\Modules\\CardAdmin\\Models\\CardAdmin',
			),
			30 =>
			array(
				'id' => 31,
				'user_id' => 1,
				'api_route_id' => 16,
				'user_type' => 'App\\Modules\\CardAdmin\\Models\\CardAdmin',
			),
			31 =>
			array(
				'id' => 33,
				'user_id' => 1,
				'api_route_id' => 5,
				'user_type' => 'App\\Modules\\Accountant\\Models\\Accountant',
			),
			32 =>
			array(
				'id' => 34,
				'user_id' => 1,
				'api_route_id' => 22,
				'user_type' => 'App\\Modules\\NormalAdmin\\Models\\NormalAdmin',
			),
			33 =>
			array(
				'id' => 35,
				'user_id' => 1,
				'api_route_id' => 21,
				'user_type' => 'App\\Modules\\NormalAdmin\\Models\\NormalAdmin',
			),
			34 =>
			array(
				'id' => 36,
				'user_id' => 1,
				'api_route_id' => 24,
				'user_type' => 'App\\Modules\\NormalAdmin\\Models\\NormalAdmin',
			),
			35 =>
			array(
				'id' => 37,
				'user_id' => 1,
				'api_route_id' => 25,
				'user_type' => 'App\\Modules\\NormalAdmin\\Models\\NormalAdmin',
			),
			36 =>
			array(
				'id' => 38,
				'user_id' => 1,
				'api_route_id' => 9,
				'user_type' => 'App\\Modules\\NormalAdmin\\Models\\NormalAdmin',
			),
			37 =>
			array(
				'id' => 39,
				'user_id' => 1,
				'api_route_id' => 8,
				'user_type' => 'App\\Modules\\NormalAdmin\\Models\\NormalAdmin',
			),
			38 =>
			array(
				'id' => 40,
				'user_id' => 1,
				'api_route_id' => 7,
				'user_type' => 'App\\Modules\\NormalAdmin\\Models\\NormalAdmin',
			),
			39 =>
			array(
				'id' => 41,
				'user_id' => 1,
				'api_route_id' => 6,
				'user_type' => 'App\\Modules\\NormalAdmin\\Models\\NormalAdmin',
			),
			40 =>
			array(
				'id' => 42,
				'user_id' => 1,
				'api_route_id' => 5,
				'user_type' => 'App\\Modules\\NormalAdmin\\Models\\NormalAdmin',
			),
			41 =>
			array(
				'id' => 43,
				'user_id' => 1,
				'api_route_id' => 4,
				'user_type' => 'App\\Modules\\NormalAdmin\\Models\\NormalAdmin',
			),
			42 =>
			array(
				'id' => 44,
				'user_id' => 1,
				'api_route_id' => 11,
				'user_type' => 'App\\Modules\\NormalAdmin\\Models\\NormalAdmin',
			),
			43 =>
			array(
				'id' => 45,
				'user_id' => 1,
				'api_route_id' => 12,
				'user_type' => 'App\\Modules\\NormalAdmin\\Models\\NormalAdmin',
			),
			44 =>
			array(
				'id' => 46,
				'user_id' => 1,
				'api_route_id' => 13,
				'user_type' => 'App\\Modules\\NormalAdmin\\Models\\NormalAdmin',
			),
			45 =>
			array(
				'id' => 47,
				'user_id' => 1,
				'api_route_id' => 14,
				'user_type' => 'App\\Modules\\NormalAdmin\\Models\\NormalAdmin',
			),
			46 =>
			array(
				'id' => 48,
				'user_id' => 1,
				'api_route_id' => 15,
				'user_type' => 'App\\Modules\\NormalAdmin\\Models\\NormalAdmin',
			),
			47 =>
			array(
				'id' => 49,
				'user_id' => 1,
				'api_route_id' => 16,
				'user_type' => 'App\\Modules\\NormalAdmin\\Models\\NormalAdmin',
			),
			48 =>
			array(
				'id' => 50,
				'user_id' => 1,
				'api_route_id' => 17,
				'user_type' => 'App\\Modules\\NormalAdmin\\Models\\NormalAdmin',
			),
			49 =>
			array(
				'id' => 51,
				'user_id' => 1,
				'api_route_id' => 18,
				'user_type' => 'App\\Modules\\NormalAdmin\\Models\\NormalAdmin',
			),
			50 =>
			array(
				'id' => 52,
				'user_id' => 1,
				'api_route_id' => 19,
				'user_type' => 'App\\Modules\\NormalAdmin\\Models\\NormalAdmin',
			),
			51 =>
			array(
				'id' => 53,
				'user_id' => 1,
				'api_route_id' => 20,
				'user_type' => 'App\\Modules\\NormalAdmin\\Models\\NormalAdmin',
			),
			52 =>
			array(
				'id' => 54,
				'user_id' => 1,
				'api_route_id' => 23,
				'user_type' => 'App\\Modules\\NormalAdmin\\Models\\NormalAdmin',
			),
			53 =>
			array(
				'id' => 55,
				'user_id' => 1,
				'api_route_id' => 1,
				'user_type' => 'App\\Modules\\AccountOfficer\\Models\\AccountOfficer',
			),
			54 =>
			array(
				'id' => 56,
				'user_id' => 1,
				'api_route_id' => 4,
				'user_type' => 'App\\Modules\\AccountOfficer\\Models\\AccountOfficer',
			),
			55 =>
			array(
				'id' => 57,
				'user_id' => 1,
				'api_route_id' => 23,
				'user_type' => 'App\\Modules\\AccountOfficer\\Models\\AccountOfficer',
			),
			56 =>
			array(
				'id' => 58,
				'user_id' => 1,
				'api_route_id' => 24,
				'user_type' => 'App\\Modules\\AccountOfficer\\Models\\AccountOfficer',
			),
			57 =>
			array(
				'id' => 59,
				'user_id' => 1,
				'api_route_id' => 25,
				'user_type' => 'App\\Modules\\AccountOfficer\\Models\\AccountOfficer',
			),
			58 =>
			array(
				'id' => 60,
				'user_id' => 1,
				'api_route_id' => 15,
				'user_type' => 'App\\Modules\\AccountOfficer\\Models\\AccountOfficer',
			),
			59 =>
			array(
				'id' => 61,
				'user_id' => 1,
				'api_route_id' => 14,
				'user_type' => 'App\\Modules\\AccountOfficer\\Models\\AccountOfficer',
			),
			60 =>
			array(
				'id' => 62,
				'user_id' => 1,
				'api_route_id' => 5,
				'user_type' => 'App\\Modules\\AccountOfficer\\Models\\AccountOfficer',
			),
			61 =>
			array(
				'id' => 63,
				'user_id' => 1,
				'api_route_id' => 4,
				'user_type' => 'App\\Modules\\Accountant\\Models\\Accountant',
			),
			62 =>
			array(
				'id' => 64,
				'user_id' => 1,
				'api_route_id' => 8,
				'user_type' => 'App\\Modules\\CustomerSupport\\Models\\CustomerSupport',
			),
			63 =>
			array(
				'id' => 65,
				'user_id' => 1,
				'api_route_id' => 5,
				'user_type' => 'App\\Modules\\CustomerSupport\\Models\\CustomerSupport',
			),
		));
	}
}
