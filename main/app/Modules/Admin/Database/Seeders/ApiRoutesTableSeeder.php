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

		\DB::table('api_routes')->insert(
			[

				[
					'path' => '/account/requests',
					'name' => 'admin.account.requests',
					'meta' => '{title: APP_NAME + \' | New Account Requests\',
					iconClass: \'home\',
					menuName: \'New Account Requests\'
				}',
					'description' => 'New Account Requests',
				],

				[
					'path' => '/admins',
					'name' => 'admin.admins.view',
					'meta' => '{title: APP_NAME + \' | View Admins\',
					iconClass: \'home\',
					menuName: \'View Admins\'
				}',
					'description' => 'View Admins',
				],

				[
					'path' => '/logs/auth-attempts',
					'name' => 'admin.logs.auth',
					'meta' => '{title: APP_NAME + \' | Auth Logs\',
					iconClass: \'home\',
					menuName: \'Auth Logs\'
				}',
					'description' => 'View Auth Logs',
				],

				[
					'path' => '/card-users',
					'name' => 'admin.card-users.view',
					'meta' => '{
					title: APP_NAME + \' | View Card Users\',
					iconClass: \'home\',
					menuName: \'View Card Users\'
				}',
					'description' => 'View Card Users',
				],

				[
					'path' => '/cards/requests',
					'name' => 'admin.cards.requests',
					'meta' => '{
					title: APP_NAME + \' | View Card Requests\',
					iconClass: \'home\',
					menuName: \'View Card Requests\'
				}',
					'description' => 'View Card Requests',
				],

				[
					'path' => '/messages',
					'name' => 'admin.messages',
					'meta' => '{title: APP_NAME + \' | Messages\',
					iconClass: \'home\',
					menuName: \'Messages\'
				}',
					'description' => 'Messages',
				],

				[
					'path' => '/normal-admins',
					'name' => 'admin.normal-admins.view',
					'meta' => '{title: APP_NAME + \' | View Normal Admins\',
					iconClass: \'home\',
					menuName: \'View Normal Admins\'
				}',
					'description' => 'View Normal Admins',
				],

				[
					'path' => '/accountants',
					'name' => 'admin.accountants.view',
					'meta' => '{title: APP_NAME + \' | View Accountants\',
					iconClass: \'home\',
					menuName: \'View Accountants\'
				}',
					'description' => 'View Accountants',
				],

				[
					'path' => '/account-officers',
					'name' => 'admin.account-officers.view',
					'meta' => '{title: APP_NAME + \' | View Account Officers\',
					iconClass: \'user\',
					menuName: \'View Account Officers\'
				}',
					'description' => 'View Account Officers',
				],

				[
					'path' => '/customer-supports',
					'name' => 'admin.card-admins.view',
					'meta' => '{title: APP_NAME + \' | View Card Admins\',
					iconClass: \'user\',
					menuName: \'View Card Admins\'
				}',
					'description' => 'View Card Admins',
				],

				[
					'path' => '/customer-supports',
					'name' => 'admin.customer-supports.view',
					'meta' => '{title: APP_NAME + \' | View Customer Support\',
					iconClass: \'user\',
					menuName: \'View Customer Support\'
				}',
					'description' => 'View Customer Support',
				],

				[
					'path' => '/dispatch-admins',
					'name' => 'admin.dispatch-admins.view',
					'meta' => '{title: APP_NAME + \' | View Dispatch Admins\',
					iconClass: \'user\',
					menuName: \'View Dispatch Admins\'
				}',
					'description' => 'View Dispatch Admins',
				],

				[
					'path' => '/sales-reps',
					'name' => 'admin.sales-reps.view',
					'meta' => '{title: APP_NAME + \' | View Sales Reps\',
						iconClass: \'user\',
						menuName: \'View Sales Reps\'
					}',
					'description' => 'View Sales Reps',
				],

				[
					'path' => '/merchants',
					'name' => 'admin.merchants.view',
					'meta' => '{title: APP_NAME + \' | View Merchants\',
						iconClass: \'user\',
						menuName: \'View Merchants\'
					}',
					'description' => 'Manage Merchants',
				],

				[
					'path' => '/cards/list',
					'name' => 'admin.cards.list',
					'meta' => '{title: APP_NAME + \' | View Debit Cards\',
					iconClass: \'user\',
					menuName: \'View Debit Cards\'
				}',
					'description' => 'View Debit Cards',
				],

				[
					'path' => '/cards/types',
					'name' => 'admin.cards.types',
					'meta' => '{title: APP_NAME + \' | Debit Card Types\',
						iconClass: \'user\',
						menuName: \'View Debit Card Types\'
					}',
					'description' => 'Manage Debit Card Types',
				],

				[
					'path' => '/cards/:rep/list',
					'name' => 'admin.cards.sales-rep.list',
					'meta' => '{title: APP_NAME + \' | View Sales Rep Debit Cards\',
					iconClass: \'user\',
					menuName: \'View Sales Rep Debit Cards\'
				}',
					'description' => 'View Sales Rep Debit Cards',
				],

				[
					'path' => '/cards/stock/request',
					'name' => 'admin.cards.stock.request',
					'meta' => '{title: APP_NAME + \' | View Sales Rep Stock Requests\',
					iconClass: \'cc-visa\',
					menuName: \'View Sales Rep Stock Requests\'
				}',
					'description' => 'View Stock Requests',
				],

				[
					'path' => '/loans/request',
					'name' => 'admin.loans.requests',
					'meta' => '{title: APP_NAME + \' | View Loan Requests\',
					iconClass: \'cc-visa\',
					menuName: \'View Loan Requests\'
				}',
					'description' => 'Manage Loan Requests',
				],

				[
					'path' => '/loans/transactions',
					'name' => 'admin.loans.transactions',
					'meta' => '{title: APP_NAME + \' | View Loan Transactions\',
					iconClass: \'cc-visa\',
					menuName: \'View Loan Transactions\'
				}',
					'description' => 'Manage Loan Transactions',
				],

			]
		);
	}
}
