/**
 * Asynchronously load view (Webpack Lazy loading compatible)
 * @param  {string}   name     the filename (basename) of the view to load.
 */
function adminView( name ) {
    return function ( resolve ) {
        require( [ '@admin-components/' + name ], resolve )
    }
}

/**
 * Asynchronously load view (Webpack Lazy loading compatible)
 * @param  {string}   name     the filename (basename) of the view to load.
 */
function normalAdminView( name ) {
    return function ( resolve ) {
        require( [ '@normalAdmin-components/' + name ], resolve )
    }
}

const APP_NAME = 'Itse FinTech Admin'

export const allRoutes = [ {
        path: '/manage-ui',
        component: adminView( 'EmptyComponent' ),
        meta: {
            iconClass: 'home',
            menuName: 'Manage UI'
        },
        children: [ {
                path: '/manage-ui/testimonials',
                component: adminView( 'ui/ManageTestimonials' ),
                name: 'admin.ui.testimonials',
                meta: {
                    title: APP_NAME + ' | Manage Testimonials',
                    iconClass: 'home',
                    menuName: 'Manage Testimonials'
                },
            },
            {
                path: '/manage-ui/faqs',
                component: adminView( 'dashboard/ManageFAQs' ),
                name: 'admin.ui.faqs',
                meta: {
                    title: APP_NAME + ' | Manage FAQs',
                    iconClass: 'home',
                    menuName: 'Manage FAQs'
                },
            },
            {
                path: '/manage-ui/slides',
                component: adminView( 'dashboard/ManageSlides' ),
                name: 'admin.ui.slides',
                meta: {
                    title: APP_NAME + ' | Manage Slideshow',
                    iconClass: 'home',
                    menuName: 'Manage Slideshow'
                },
            },
            {
                path: '/manage-ui/highlights', //the images under the about us video
                component: adminView( 'dashboard/ManageHighlights' ),
                name: 'admin.ui.highlights',
                meta: {
                    title: APP_NAME + ' | Manage Highlights',
                    iconClass: 'home',
                    menuName: 'Manage Highlights'
                },
            },
        ],

    },

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
        path: '/admins',
        component: adminView( 'EmptyComponent' ),
        meta: {
            iconClass: 'home',
            menuName: 'Manage Admins'
        },
        children: [ {
                path: '/admins',
                component: adminView( 'admins/ManageAdmins' ),
                name: 'admin.admins.view',
                meta: {
                    title: APP_NAME + ' | View Admins',
                    iconClass: 'home',
                    menuName: 'View Admins'
                },
            },
            {
                path: '/admins/:id/route-permissions',
                component: adminView( 'dashboard/ManageAdmins' ),
                name: 'admin.admins.permissions',
                props: true,
                meta: {
                    title: APP_NAME + ' | View Admin Permissions',
                    iconClass: 'home',
                    menuName: 'View Admin Permission',
                    skip: true
                },
            }
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
