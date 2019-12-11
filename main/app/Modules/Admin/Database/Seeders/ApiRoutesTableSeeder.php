<?php
namespace App\Modules\Admin\Database\Seeders;

use Illuminate\Database\Seeder;

class ApiRoutesTableSeeder extends Seeder
{

	/**
	 * Auto generated seed file
	 *
	 * @return void
	 */
	public function run()
	{


		\DB::table('api_routes')->delete();

		\DB::table('api_routes')->insert(array(

			8 =>
			array(
				'path' => '/account/requests',
				'name' => 'admin.account.requests',
				'meta' => '{title: APP_NAME + \' | New Account Requests\',
					iconClass: \'home\',
					menuName: \'New Account Requests\'
				}',
				'description' => 'New Account Requests',
			),

			10 =>
			array(
				'path' => '/logs/auth-attempts',
				'name' => 'admin.logs.auth',
				'meta' => '{title: APP_NAME + \' | Auth Logs\',
					iconClass: \'home\',
					menuName: \'Auth Logs\'
				}',
				'description' => 'View Auth Logs',
			),
			11 =>
			array(
				'path' => '/admins',
				'name' => 'admin.admins.view',
				'meta' => '{title: APP_NAME + \' | View Admins\',
					iconClass: \'home\',
					menuName: \'View Admins\'
				}',
				'description' => 'View Admins',
			),
			12 =>
			array(
				'path' => '/messages',
				'name' => 'admin.messages',
				'meta' => '{title: APP_NAME + \' | Messages\',
					iconClass: \'home\',
					menuName: \'Messages\'
				}',
				'description' => 'Messages',
			),
			13 =>
			array(
				'path' => '/normal-admins',
				'name' => 'admin.normal-admins.view',
				'meta' => '{title: APP_NAME + \' | View Admins\',
					iconClass: \'home\',
					menuName: \'View Normal Admins\'
				}',
				'description' => 'View Normal Admins',
			),
			14 =>
			array(
				'path' => '/normal-admins',
				'name' => 'admin.accountants.view',
				'meta' => '{title: APP_NAME + \' | View Admins\',
					iconClass: \'home\',
					menuName: \'View Accountants\'
				}',
				'description' => 'View Accountants',
			),
		));
	}
}
