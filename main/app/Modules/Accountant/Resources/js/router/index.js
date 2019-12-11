import Vue from 'vue'
import Router from 'vue-router'

import {
    allRoutes,
    accoutantAuthRoutes
} from "@admin-assets/js/router/routes";

Vue.use( Router )

const APP_NAME = 'Itse FinTech Admin'

/**
 * Asynchronously load view (Webpack Lazy loading compatible)
 * @param  {string}   name     the filename (basename) of the view to load.
 */
function view( name ) {
    return function ( resolve ) {
        require( [ '@accountant-components/' + name ], resolve )
    }
}

const processRoutes = async ( route ) => {
    try {
        const sar = axios.post( '/accountant/api/test-route-permission', {
            route
        } )
        let pp = await sar;
        if ( undefined == pp ) return false
        return pp.data.rsp;
    } catch ( error ) {}
}

const getRoutes = async () => {
    let permittedRoutes = [ {
            path: '/',
            component: view( 'dashboard/AccountantDashboard' ),
            name: 'accountant.root',
            meta: {
                title: APP_NAME + ' | Dashboard',
                iconClass: 'home',
                menuName: 'Dashboard'
            },
        },
        {
            path: '*',
            name: 'accountant.catch-all',
            redirect: {
                name: 'accountant.root'
            }
        }
    ];
    for ( const route of allRoutes ) {
        if ( route.children ) {
            let childRoutes = [];
            for ( const subRoute of route.children ) {
                if ( subRoute.meta.skip ) {} else {
                    const result = await processRoutes( subRoute.name );
                    if ( result === true ) childRoutes.push( subRoute )
                }
            }
            if ( childRoutes.length ) {
                route.children = childRoutes;
                permittedRoutes.push( route )
            }
        } else {
            if ( route.meta.skip ) {} else {
                const result = await processRoutes( route.name );
                if ( result === true ) permittedRoutes.push( route )
            }
        }
    }
    return permittedRoutes;
}

export const routeGenerator = async () => new Router( {
    mode: 'history',
    base: '/accountant/',
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
    base: '/accountant/',
    routes: accoutantAuthRoutes,
} )
