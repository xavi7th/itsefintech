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

			1 =>
			array(
				'path' => '/account/requests',
				'name' => 'admin.account.requests',
				'meta' => '{title: APP_NAME + \' | New Account Requests\',
					iconClass: \'home\',
					menuName: \'New Account Requests\'
				}',
				'description' => 'New Account Requests',
			),
			2 =>
			array(
				'path' => '/admins',
				'name' => 'admin.admins.view',
				'meta' => '{title: APP_NAME + \' | View Admins\',
					iconClass: \'home\',
					menuName: \'View Admins\'
				}',
				'description' => 'View Admins',
			),

			3 =>
			array(
				'path' => '/logs/auth-attempts',
				'name' => 'admin.logs.auth',
				'meta' => '{title: APP_NAME + \' | Auth Logs\',
					iconClass: \'home\',
					menuName: \'Auth Logs\'
				}',
				'description' => 'View Auth Logs',
			),
			4 =>
			array(
				'path' => '/card-users',
				'name' => 'admin.card-users.view',
				'meta' => '{
					title: APP_NAME + \' | View Card Users\',
					iconClass: \'home\',
					menuName: \'View Card Users\'
				}',
				'description' => 'View Card Users',
			),

			5 =>
			array(
				'path' => '/cards/requests',
				'name' => 'admin.cards.requests',
				'meta' => '{
					title: APP_NAME + \' | View Card Requests\',
					iconClass: \'home\',
					menuName: \'View Card Requests\'
				}',
				'description' => 'View Card Requests',
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
				'meta' => '{title: APP_NAME + \' | View Normal Admins\',
					iconClass: \'home\',
					menuName: \'View Normal Admins\'
				}',
				'description' => 'View Normal Admins',
			),
			14 =>
			array(
				'path' => '/accountants',
				'name' => 'admin.accountants.view',
				'meta' => '{title: APP_NAME + \' | View Accountants\',
					iconClass: \'home\',
					menuName: \'View Accountants\'
				}',
				'description' => 'View Accountants',
			),
			15 =>
			array(
				'path' => '/account-officers',
				'name' => 'admin.account-officers.view',
				'meta' => '{title: APP_NAME + \' | View Account Officers\',
					iconClass: \'user\',
					menuName: \'View Account Officers\'
				}',
				'description' => 'View Account Officers',
			),
			16 =>
			array(
				'path' => '/customer-supports',
				'name' => 'admin.card-admins.view',
				'meta' => '{title: APP_NAME + \' | View Card Admins\',
					iconClass: \'user\',
					menuName: \'View Card Admins\'
				}',
				'description' => 'View Card Admins',
			),
			17 =>
			array(
				'path' => '/customer-supports',
				'name' => 'admin.customer-supports.view',
				'meta' => '{title: APP_NAME + \' | View Customer Support\',
					iconClass: \'user\',
					menuName: \'View Customer Support\'
				}',
				'description' => 'View Customer Support',
			),
			18 =>
			array(
				'path' => '/dispatch-admins',
				'name' => 'admin.dispatch-admins.view',
				'meta' => '{title: APP_NAME + \' | View Dispatch Admins\',
					iconClass: \'user\',
					menuName: \'View Dispatch Admins\'
				}',
				'description' => 'View Dispatch Admins',
			),
			19 =>
			array(
				'path' => '/sales-reps',
				'name' => 'admin.sales-reps.view',
				'meta' => '{title: APP_NAME + \' | View Sales Reps\',
					iconClass: \'user\',
					menuName: \'View Sales Reps\'
				}',
				'description' => 'View Sales Reps',
			),
			20 =>
			array(
				'path' => '/cards/list',
				'name' => 'admin.cards.list',
				'meta' => '{title: APP_NAME + \' | View Debit Cards\',
					iconClass: \'user\',
					menuName: \'View Debit Cards\'
				}',
				'description' => 'View Debit Cards',
			),
			21 =>
			array(
				'path' => '/cards/:rep/list',
				'name' => 'admin.cards.sales-rep.list',
				'meta' => '{title: APP_NAME + \' | View Sales Rep Debit Cards\',
					iconClass: \'user\',
					menuName: \'View Sales Rep Debit Cards\'
				}',
				'description' => 'View Sales Rep Debit Cards',
			),
			22 =>
			array(
				'path' => '/cards/stock/request',
				'name' => 'admin.cards.stock.request',
				'meta' => '{title: APP_NAME + \' | View Sales Rep Stock Requests\',
					iconClass: \'cc-visa\',
					menuName: \'View Sales Rep Stock Requests\'
				}',
				'description' => 'View Stock Requests',
			),
		));
	}
}
