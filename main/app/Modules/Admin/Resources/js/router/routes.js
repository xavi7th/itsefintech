/**
 * Asynchronously load view (Webpack Lazy loading compatible)
 * @param  {string}   name     the filename (basename) of the view to load.
 */
function adminView( name ) {
    return function ( resolve ) {
        require( [ '@admin-components/' + name ], resolve )
    }
}

function normalAdminView( name ) {
    return function ( resolve ) {
        require( [ '@normalAdmin-components/' + name ], resolve )
    }
}

const accountantView = function ( name ) {
    return function ( resolve ) {
        require( [ '@accountant-components/' + name ], resolve )
    }
}

const accountOfficerView = function ( name ) {
    return function ( resolve ) {
        require( [ '@accountOfficer-components/' + name ], resolve )
    }
}

const cardAdminView = function ( name ) {
    return function ( resolve ) {
        require( [ '@cardAdmin-components/' + name ], resolve )
    }
}

const customerSupportView = function ( name ) {
    return function ( resolve ) {
        require( [ '@customerSupport-components/' + name ], resolve )
    }
}

const dispatchAdminView = function ( name ) {
    return function ( resolve ) {
        require( [ '@dispatchAdmin-components/' + name ], resolve )
    }
}

const salesRepView = function ( name ) {
    return function ( resolve ) {
        require( [ '@salesRep-components/' + name ], resolve )
    }
}

const APP_NAME = 'Itse FinTech Admin'

export const allRoutes = [

    {
        path: '/logs',
        component: {
            render: h => h( 'router-view' )
        },
        meta: {
            iconClass: 'home',
            menuName: 'View Activity Logs'
        },
        children: [ {
            path: '/logs/auth-attempts', //log failed attempts or all attempts for admin to be able to know if too many people are trying to access it
            component: adminView( 'dashboard/ManageActivityLogs' ),
            name: 'admin.logs.auth',
            meta: {
                title: APP_NAME + ' | Auth Logs',
                iconClass: 'home',
                menuName: 'Auth Logs'
            },
        } ]
    },
    {
        path: '/cards',
        component: adminView( 'EmptyComponent' ),
        meta: {
            iconClass: 'home',
            menuName: 'Manage Debit Cards'
        },
        children: [ {
                path: '/cards/list',
                component: adminView( 'cards/ManageDebitCards' ),
                name: 'admin.cards.list',
                meta: {
                    title: APP_NAME + ' | All Debit Cards',
                    iconClass: 'home',
                    menuName: 'All Debit Cards'
                },
            },
            {
                path: '/cards/:rep/list',
                component: adminView( 'cards/ManageDebitCards' ),
                name: 'admin.cards.sales-rep.list',
                meta: {
                    title: APP_NAME + ' | Sales Rep Debit Cards',
                    iconClass: 'home',
                    menuName: 'Sales Rep Debit Cards',
                    navSkip: true
                },
            },
            {
                path: '/cards/requests',
                component: adminView( 'cards/ManageDebitCardRequests' ),
                name: 'admin.cards.requests',
                meta: {
                    title: APP_NAME + ' | All Debit Card Requests',
                    iconClass: 'home',
                    menuName: 'All Debit Card Requests'
                },
            },
        ]
    },
    {
        path: '/users',
        component: adminView( 'EmptyComponent' ),
        meta: {
            iconClass: 'home',
            menuName: 'Manage User Types'
        },
        children: [ {
                path: '/users/card-users',
                component: adminView( 'admins/ManageCardUsers' ),
                name: 'admin.card-users.view',
                meta: {
                    title: APP_NAME + ' | View Card Users',
                    iconClass: 'home',
                    menuName: 'View Card Users'
                },
            },
            {
                path: '/users/admins',
                component: adminView( 'admins/ManageAdmins' ),
                name: 'admin.admins.view',
                meta: {
                    title: APP_NAME + ' | View Admins',
                    iconClass: 'home',
                    menuName: 'View Admins'
                },
            },
            {
                path: '/users/normal-admins',
                component: adminView( 'admins/ManageNormalAdmins' ),
                name: 'admin.normal-admins.view',
                meta: {
                    title: APP_NAME + ' | View Normal Admins',
                    iconClass: 'user',
                    menuName: 'View Normal Admins'
                },
            },
            {
                path: '/users/accountants',
                component: adminView( 'admins/ManageAccountants' ),
                name: 'admin.accountants.view',
                meta: {
                    title: APP_NAME + ' | View Accountants',
                    iconClass: 'user',
                    menuName: 'View Accountants'
                },
            },
            {
                path: '/users/account-officers',
                component: adminView( 'admins/ManageAccountOfficers' ),
                name: 'admin.account-officers.view',
                meta: {
                    title: APP_NAME + ' | View Account Officers',
                    iconClass: 'user',
                    menuName: 'View Account Officers'
                },
            },
            {
                path: '/users/card-admins',
                component: adminView( 'admins/ManageCardAdmins' ),
                name: 'admin.card-admins.view',
                meta: {
                    title: APP_NAME + ' | View Card Admins',
                    iconClass: 'user',
                    menuName: 'View Card Admins'
                },
            },
            {
                path: '/users/customer-supports',
                component: adminView( 'admins/ManageCustomerSupport' ),
                name: 'admin.customer-supports.view',
                meta: {
                    title: APP_NAME + ' | View Customer Support',
                    iconClass: 'user',
                    menuName: 'View Customer Support'
                },
            },
            {
                path: '/users/dispatch-admins',
                component: adminView( 'admins/ManageDispatchAdmin' ),
                name: 'admin.dispatch-admins.view',
                meta: {
                    title: APP_NAME + ' | View Dispatch Admin',
                    iconClass: 'user',
                    menuName: 'View Dispatch Admin'
                },
            },
            {
                path: '/users/sales-reps',
                component: adminView( 'admins/ManageSalesRep' ),
                name: 'admin.sales-reps.view',
                meta: {
                    title: APP_NAME + ' | View Sales Representatives',
                    iconClass: 'user',
                    menuName: 'View Sales Representatives'
                },
            },
        ]
    },

    {
        path: '/messages',
        component: adminView( 'dashboard/ManageMessages' ),
        name: 'admin.messages',
        meta: {
            title: APP_NAME + ' | Messages',
            iconClass: 'home',
            menuName: 'Messages'
        },
    }
]

export const authRoutes = [ {
        path: '/login',
        component: adminView( 'auth/Login' ),
        meta: {}
    },
    {
        path: '*',
        redirect: {
            path: '/'
        }
    }
]

export const normalAdminAuthRoutes = [ {
        path: '/login',
        component: normalAdminView( 'auth/Login' ),
        meta: {}
    },
    {
        path: '*',
        redirect: {
            path: '/'
        }
    }
]

export const accoutantAuthRoutes = [ {
        path: '/login',
        component: accountantView( 'auth/Login' ),
        meta: {}
    },
    {
        path: '*',
        redirect: {
            path: '/'
        }
    }
]

export const accountOfficerAuthRoutes = [ {
        path: '/login',
        component: accountOfficerView( 'auth/Login' ),
        meta: {}
    },
    {
        path: '*',
        redirect: {
            path: '/'
        }
    }
]

export const cardAdminAuthRoutes = [ {
        path: '/login',
        component: cardAdminView( 'auth/Login' ),
        meta: {}
    },
    {
        path: '*',
        redirect: {
            path: '/'
        }
    }
]

export const customerSupportAuthRoutes = [ {
        path: '/login',
        component: customerSupportView( 'auth/Login' ),
        meta: {}
    },
    {
        path: '*',
        redirect: {
            path: '/'
        }
    }
]

export const dispatchAdminAuthRoutes = [ {
        path: '/login',
        component: dispatchAdminView( 'auth/Login' ),
        meta: {}
    },
    {
        path: '*',
        redirect: {
            path: '/'
        }
    }
]

export const salesRepAuthRoutes = [ {
        path: '/login',
        component: salesRepView( 'auth/Login' ),
        meta: {}
    },
    {
        path: '*',
        redirect: {
            path: '/'
        }
    }
]
