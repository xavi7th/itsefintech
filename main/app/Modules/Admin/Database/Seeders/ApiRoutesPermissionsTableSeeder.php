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
		));
	}
}
