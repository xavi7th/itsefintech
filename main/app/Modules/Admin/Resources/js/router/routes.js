/**
 * Asynchronously load view (Webpack Lazy loading compatible)
 * @param  {string}   name     the filename (basename) of the view to load.
 */
function adminView(name) {
	return () => import( /* webpackChunkName: "js/admin-" */ `@admin-components/${name}.vue`)
		.then(module => module.default);
}

function normalAdminView(name) {
	return () => import( /* webpackChunkName: "js/normalAdmin-" */ `@normalAdmin-components/${name}.vue`)
		.then(module => module.default);
}

const accountantView = function(name) {
	return () => import( /* webpackChunkName: "js/accountant-" */ `@accountant-components/${name}.vue`)
		.then(module => module.default);
}

const accountOfficerView = function(name) {
	return () => import( /* webpackChunkName: "js/accountOfficer-" */ `@accountOfficer-components/${name}.vue`)
		.then(module => module.default);
}

const cardAdminView = function(name) {
	return () => import( /* webpackChunkName: "js/cardAdmin-" */ `@cardAdmin-components/${name}.vue`)
		.then(module => module.default);
}

const customerSupportView = function(name) {
	return () => import( /* webpackChunkName: "js/customerSupport-" */ `@customerSupport-components/${name}.vue`)
		.then(module => module.default);
}

const salesRepView = function(name) {
	return () => import( /* webpackChunkName: "js/salesRep-" */ `@salesRep-components/${name}.vue`)
		.then(module => module.default);
}

const APP_NAME = 'Capital X';

export const allRoutes = [

	{
		path: '/logs',
		component: adminView('EmptyComponent'),
		meta: {
			iconClass: 'home',
			menuName: 'View Activity Logs'
		},
		children: [
            // {
            //       path: '/logs/auth-attempts', //log failed attempts or all attempts for admin to be able to know if too many people are trying to access it
            //       component: adminView( 'logs/ManageAuthActivityLogs' ),
            //       name: 'admin.logs.auth',
            //       meta: {
            //           title: 'Auth Logs | ' + APP_NAME,
            //           iconClass: 'home',
            //           menuName: 'Auth Logs'
            //       },
            //   },
			{
				path: '/logs/activities',
				component: adminView('logs/ManageActivityLogs'),
				name: 'admin.logs',
				meta: {
					title: 'Activities | ' + APP_NAME,
					iconClass: 'home',
					menuName: 'View Activities'
				},
            }
        ]
    },
	{
		path: '/loans',
		component: adminView('EmptyComponent'),
		meta: {
			iconClass: 'hand-holding-usd',
			menuName: 'Manage Loans'
		},
		children: [{
				path: '/vouchers',
				component: adminView('vouchers/ManageVouchers'),
				name: 'admin.vouchers',
				meta: {
					title: 'Vouchers | ' + APP_NAME,
					iconClass: 'home',
					menuName: 'Vouchers'
				},
            },
			{
				path: '/vouchers/requests',
				component: adminView('vouchers/ManageVoucherRequests'),
				name: 'admin.voucher.requests',
				meta: {
					title: 'Manage Voucher Requests | ' + APP_NAME,
					iconClass: 'home',
					menuName: 'Manage Voucher Requests'
				},
            },
			{
				path: '/vouchers/transactions',
				component: adminView('vouchers/ManageVoucherTransactions'),
				name: 'admin.voucher.transactions',
				meta: {
					title: 'Voucher Transactions | ' + APP_NAME,
					iconClass: 'home',
					menuName: 'Voucher Transactions'
				},
            },
			{
				path: '/loans/requests',
				component: adminView('loans/ManageLoanRequests'),
				name: 'admin.loans.requests',
				meta: {
					title: 'All Loan Requests | ' + APP_NAME,
					iconClass: 'home',
					menuName: 'All Loan Requests'
				},
            },
			{
				path: '/loans/transactions',
				component: adminView('loans/ManageLoanTransactions'),
				name: 'admin.loans.transactions',
				meta: {
					title: 'All Loan Transactions | ' + APP_NAME,
					iconClass: 'home',
					menuName: 'All Loan Transactions'
				},
            },
			{
				path: '/loans/recovery',
				component: adminView('loans/ManageLoanRecovery'),
				name: 'admin.loans.recovery',
				meta: {
					title: 'Loan Recovery | ' + APP_NAME,
					iconClass: 'home',
					menuName: 'Loan Recovery'
				},
            }
        ]
    },
	{
		path: '/cards',
		component: adminView('EmptyComponent'),
		meta: {
			iconClass: 'cc-visa',
			menuName: 'Manage Debit Cards'
		},
		children: [{
				path: '/cards/types',
				component: adminView('cards/ManageDebitCardTypes'),
				name: 'admin.cards.types',
				meta: {
					title: 'Debit Card Types | ' + APP_NAME,
					iconClass: 'home',
					menuName: 'Debit Card Types'
				},
            },
			{
				path: '/cards/list',
				component: adminView('cards/ManageDebitCards'),
				name: 'admin.cards.list',
				meta: {
					title: 'All Debit Cards | ' + APP_NAME,
					iconClass: 'home',
					menuName: 'All Debit Cards',
					// navSkip: true
				},
            },
			{
				path: '/cards/requests',
				component: adminView('cards/ManageDebitCardRequests'),
				name: 'admin.cards.requests',
				meta: {
					title: 'All Debit Card Requests | ' + APP_NAME,
					iconClass: 'home',
					menuName: 'All Debit Card Requests'
				},
            },
			{
				path: '/cards/funding/request',
				component: adminView('cards/ManageDebitCardFundingRequests'),
				name: 'admin.cards.funding.request',
				meta: {
					title: 'All Debit Card Funding Requests | ' + APP_NAME,
					iconClass: 'cc-visa',
					menuName: 'All Debit Card Funding Requests'
				},
            },
			{
				path: '/cards/stock/request',
				component: adminView('cards/ManageDebitCardStockRequests'),
				name: 'admin.cards.stock.request',
				meta: {
					title: 'All Debit Card Stock Requests | ' + APP_NAME,
					iconClass: 'cc-visa',
					menuName: 'All Debit Card Stock Requests'
				},
            },
        ]
    },
	{
		path: '/users',
		component: adminView('EmptyComponent'),
		meta: {
			iconClass: 'home',
			menuName: 'Manage User Types'
		},
		children: [{
				path: '/users/card-users',
				component: adminView('admins/ManageCardUsers'),
				name: 'admin.card-users.view',
				meta: {
					title: 'View Card Users | ' + APP_NAME,
					iconClass: 'home',
					menuName: 'View Card Users'
				},
            },
			{
				path: '/users/admins',
				component: adminView('admins/ManageAdmins'),
				name: 'admin.admins.view',
				meta: {
					title: 'View Admins | ' + APP_NAME,
					iconClass: 'home',
					menuName: 'View Admins'
				},
            },
			{
				path: '/users/normal-admins',
				component: adminView('admins/ManageNormalAdmins'),
				name: 'admin.normal-admins.view',
				meta: {
					title: 'View Normal Admins | ' + APP_NAME,
					iconClass: 'user',
					menuName: 'View Normal Admins'
				},
            },
			{
				path: '/users/accountants',
				component: adminView('admins/ManageAccountants'),
				name: 'admin.accountants.view',
				meta: {
					title: 'View Accountants | ' + APP_NAME,
					iconClass: 'user',
					menuName: 'View Accountants'
				},
            },
			{
				path: '/users/account-officers',
				component: adminView('admins/ManageAccountOfficers'),
				name: 'admin.account-officers.view',
				meta: {
					title: 'View Account Officers | ' + APP_NAME,
					iconClass: 'user',
					menuName: 'View Account Officers'
				},
            },
			{
				path: '/users/card-admins',
				component: adminView('admins/ManageCardAdmins'),
				name: 'admin.card-admins.view',
				meta: {
					title: 'View Card Admins | ' + APP_NAME,
					iconClass: 'user',
					menuName: 'View Card Admins'
				},
            },
			{
				path: '/users/customer-supports',
				component: adminView('admins/ManageCustomerSupport'),
				name: 'admin.customer-supports.view',
				meta: {
					title: 'View Customer Support | ' + APP_NAME,
					iconClass: 'user',
					menuName: 'View Customer Support'
				},
            },
			{
				path: '/users/sales-reps',
				component: adminView('admins/ManageSalesRep'),
				name: 'admin.sales-reps.view',
				meta: {
					title: 'View Sales Representatives | ' + APP_NAME,
					iconClass: 'user',
					menuName: 'View Sales Representatives'
				},
            },
			{
				path: '/users/merchant-categories',
				component: adminView('merchants/ManageMerchantCategories'),
				name: 'admin.merchant-categories.view',
				meta: {
					title: 'View MerchantCategories | ' + APP_NAME,
					iconClass: 'user',
					menuName: 'View Merchant Categories'
				},
            },
			{
				path: '/users/merchants',
				component: adminView('merchants/ManageMerchants'),
				name: 'admin.merchants.view',
				meta: {
					title: 'View Merchants | ' + APP_NAME,
					iconClass: 'user',
					menuName: 'View Merchants'
				},
            },
        ]
    },

	{
		path: '/tickets',
		component: customerSupportView('tickets/ManageTickets'),
		name: 'customerSupport.tickets',
		meta: {
			title: 'Customer Service | ' + APP_NAME,
			iconClass: 'home',
			menuName: 'Tickets'
		},
    },

    // {
    //     path: '/messages',
    //     component: adminView( 'dashboard/ManageMessages' ),
    //     name: 'admin.messages',
    //     meta: {
    //         title: 'Messages | ' + APP_NAME,
    //         iconClass: 'home',
    //         menuName: 'Messages'
    //     },
    // }
]

export const authRoutes = [{
		path: '/login',
		component: adminView('auth/Login'),
		meta: {}
    },
	{
		path: '*',
		redirect: {
			path: '/'
		}
    }
]

export const normalAdminAuthRoutes = [{
		path: '/login',
		component: normalAdminView('auth/Login'),
		meta: {}
    },
	{
		path: '*',
		redirect: {
			path: '/'
		}
    }
]

export const accoutantAuthRoutes = [{
		path: '/login',
		component: accountantView('auth/Login'),
		meta: {}
    },
	{
		path: '*',
		redirect: {
			path: '/'
		}
    }
]

export const accountOfficerAuthRoutes = [{
		path: '/login',
		component: accountOfficerView('auth/Login'),
		meta: {}
    },
	{
		path: '*',
		redirect: {
			path: '/'
		}
    }
]

export const cardAdminAuthRoutes = [{
		path: '/login',
		component: cardAdminView('auth/Login'),
		meta: {}
    },
	{
		path: '*',
		redirect: {
			path: '/'
		}
    }
]

export const customerSupportAuthRoutes = [{
		path: '/login',
		component: customerSupportView('auth/Login'),
		meta: {}
    },
	{
		path: '*',
		redirect: {
			path: '/'
		}
    }
]

export const salesRepAuthRoutes = [{
		path: '/login',
		component: salesRepView('auth/Login'),
		meta: {}
    },
	{
		path: '*',
		redirect: {
			path: '/'
		}
    }
]
