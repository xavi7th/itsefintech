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
        path: '/users',
        component: adminView( 'EmptyComponent' ),
        meta: {
            iconClass: 'home',
            menuName: 'Manage Admins'
        },
        children: [ {
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
                path: 'normal-admins',
                component: adminView( 'admins/ManageNormalAdmins' ),
                name: 'admin.normal-admins.view',
                meta: {
                    title: APP_NAME + ' | View Normal Admins',
                    iconClass: 'user',
                    menuName: 'View Normal Admins'
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
