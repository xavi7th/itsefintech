<?php
namespace App\Modules\Admin\Database\Seeders;

use Illuminate\Database\Seeder;

class DepartmentsTableSeeder extends Seeder
{

	/**
	 * Auto generated seed file
	 *
	 * @return void
	 */
	public function run()
	{


		\DB::table('departments')->delete();

		\DB::table('departments')->insert(array(
			0 =>
			array(
				'id' => 1,
				'name' => 'Admin',
				'display_name' => 'Super Admin',
			),
			1 =>
			array(
				'id' => 2,
				'name' => 'Customer Support',
				'display_name' => 'Customer Support',
			),
			2 =>
			array(
				'id' => 3,
				'name' => 'Card Admin',
				'display_name' => 'Card Control',
			),
			3 =>
			array(
				'id' => 4,
				'name' => 'Normal Admin',
				'display_name' => 'Admin',
			),
			4 =>
			array(
				'id' => 5,
				'name' => 'Accountant',
				'display_name' => 'Accountant',
			),
			5 =>
			array(
				'id' => 6,
				'name' => 'Account Officer',
				'display_name' => 'Account Officer',
			),
			6 =>
			array(
				'id' => 7,
				'name' => 'Sales Rep',
				'display_name' => 'Sales Representative',
			),
		));
	}
}
