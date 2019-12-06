import Vue from 'vue'
import Router from 'vue-router'

/**
 * Asynchronously load view (Webpack Lazy loading compatible)
 * @param  {string}   name     the filename (basename) of the view to load.
 */
function view( name ) {
	return function ( resolve ) {
		require( [ '@admin-components/' + name ], resolve )
	}
}

Vue.use( Router )

const APP_NAME = 'Amju Unique Admin'

const allRoutes = [ {
		path: '/',
		component: view( 'dashboard/AdminDashboard' ),
		name: 'admin.root',
		meta: {
			title: APP_NAME + ' | Dashboard',
			iconClass: 'home',
			menuName: 'Dashboard'
		},
	},
	{
		path: 'admin.ui',
		component: view( 'EmptyComponent' ),
		meta: {
			iconClass: 'home',
			menuName: 'Manage UI'
		},
		children: [ {
				path: '/manage-ui/testimonials',
				component: view( 'ui/ManageTestimonials' ),
				name: 'admin.ui.testimonials',
				meta: {
					title: APP_NAME + ' | Manage Testimonials',
					iconClass: 'home',
					menuName: 'Manage Testimonials'
				},
			},
			{
				path: '/manage-ui/faqs',
				component: view( 'dashboard/ManageFAQs' ),
				name: 'admin.ui.faqs',
				meta: {
					title: APP_NAME + ' | Manage FAQs',
					iconClass: 'home',
					menuName: 'Manage FAQs'
				},
			},
			{
				path: '/manage-ui/slides',
				component: view( 'dashboard/ManageSlides' ),
				name: 'admin.ui.slides',
				meta: {
					title: APP_NAME + ' | Manage Slideshow',
					iconClass: 'home',
					menuName: 'Manage Slideshow'
				},
			},
			{
				path: '/manage-ui/highlights', //the images under the about us video
				component: view( 'dashboard/ManageHighlights' ),
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
		path: 'admin.media',
		component: view( 'EmptyComponent' ),
		meta: {
			iconClass: 'home',
			menuName: 'Manage Media'
		},
		children: [ {
			path: '/manage-media/video',
			component: view( 'dashboard/ManageVideos' ),
			name: 'admin.media.video',
			meta: {
				title: APP_NAME + ' | Manage Videos',
				iconClass: 'home',
				menuName: 'Manage Videos'
			},
		}, {
			path: '/manage-media/news',
			component: view( 'dashboard/ManageNews' ),
			name: 'admin.media.news',
			meta: {
				title: APP_NAME + ' | Manage News',
				iconClass: 'home',
				menuName: 'Manage News'
			},
		}, {
			path: '/manage-media/gallery',
			component: view( 'dashboard/ManageGallery' ),
			name: 'admin.media.gallery',
			meta: {
				title: APP_NAME + ' | Manage Gallery',
				iconClass: 'home',
				menuName: 'Manage Gallery'
			},
		}, ]
	},
	{
		path: 'admin.account',
		component: view( 'EmptyComponent' ),
		meta: {
			iconClass: 'home',
			menuName: 'Manage Accounts'
		},
		children: [ {
			path: '/account/requests',
			component: view( 'dashboard/AccountRequests' ),
			name: 'admin.account.requests',
			meta: {
				title: APP_NAME + ' | New Account Requests',
				iconClass: 'home',
				menuName: 'New Account Requests'
			},
		}, ]
	},

	{
		path: '/vacancies',
		component: view( 'dashboard/ManageVacancies' ),
		name: 'admin.vacancies',
		meta: {
			title: APP_NAME + ' | Vacancies',
			iconClass: 'home',
			menuName: 'Vacancies'
		},
	},
	{
		path: 'admin.logs',
		component: {
			render: h => h( 'router-view' )
		},
		meta: {
			iconClass: 'home',
			menuName: 'View Activity Logs'
		},
		children: [ {
			path: '/logs/auth-attempts', //log failed attempts or all attempts for admin to be able to know if too many people are trying to access it
			component: view( 'dashboard/ManageActivityLogs' ),
			name: 'admin.logs.auth',
			meta: {
				title: APP_NAME + ' | Auth Logs',
				iconClass: 'home',
				menuName: 'Auth Logs'
			},
		} ]
	},
	{
		path: 'admin.admins',
		component: view( 'EmptyComponent' ),
		meta: {
			iconClass: 'home',
			menuName: 'Manage Admins'
		},
		children: [ {
				path: '/admins',
				component: view( 'admins/ManageAdmins' ),
				name: 'admin.admins.view',
				meta: {
					title: APP_NAME + ' | View Admins',
					iconClass: 'home',
					menuName: 'View Admins'
				},
			},
			{
				path: '/admins/:id/route-permissions',
				component: view( 'dashboard/ManageAdmins' ),
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
		component: view( 'dashboard/ManageMessages' ),
		name: 'admin.messages',
		meta: {
			title: APP_NAME + ' | Messages',
			iconClass: 'home',
			menuName: 'Messages'
		},
	}
]

const authRoutes = [ {
		path: '/login',
		component: view( 'auth/Login' ),
		meta: {}
	},
	{
		path: '*',
		redirect: {
			path: '/'
		}
	}
]

const processRoutes = async ( route ) => {
	try {
		const sar = axios.post( '/admin-panel/api/test-route-permission', {
			route
		} )
		let pp = await sar;
		if ( undefined == pp ) return false
		return pp.data.rsp;
	} catch ( error ) {}
}

const getRoutes = async () => {
	let permittedRoutes = [ {
		path: '*',
		name: 'admin.catch-all',
		redirect: {
			name: 'admin.root'
		}
	} ];
	for ( const route of allRoutes ) {
		if ( route.children ) {
			let childRoutes = [];
			for ( const subRoute of route.children ) {
				const result = await processRoutes( subRoute.name );
				if ( result === true ) childRoutes.push( subRoute )
			}
			if ( childRoutes.length ) {
				route.children = childRoutes;
				permittedRoutes.push( route )
			}
		} else {
			const result = await processRoutes( route.name );
			if ( result === true ) permittedRoutes.push( route )
		}
	}
	return permittedRoutes;
}

export const routeGenerator = async () => new Router( {
	mode: 'history',
	base: '/admin-panel/',
	scrollBehavior( to, from, savedPosition ) {
		if ( savedPosition ) {
			return savedPosition
		} else {
			return {
				x: 0,
				y: 0,
			}
		}
	},
	routes: await getRoutes(),
} )

export const authRouter = new Router( {
	mode: 'history',
	base: '/admin-panel/',
	routes: authRoutes,
} )
