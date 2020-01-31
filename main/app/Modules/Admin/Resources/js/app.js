import '@admin-assets/js/bootstrap'
import Vue from 'vue'
import VeeValidate from 'vee-validate'
import App from './AdminAppComponent'
import PageHeader from "@admin-components/partials/PageHeaderComponent";
import Vue2Filters from 'vue2-filters'
const {
    routeGenerator
} = require( './router' )

import LoadScript from 'vue-plugin-load-script'

Vue.use( Vue2Filters )

Vue.filter( 'Naira', function ( value, symbol ) {
    let currency = Vue.filter( 'currency' )
    symbol = '₦'
    return currency( value, symbol, 2, {
        thousandsSeparator: ',',
        decimalSeparator: '.'
    } )
} )

Vue.use( VeeValidate, {
    fieldsBagName: 'formFields'
} )
Vue.use( LoadScript )

routeGenerator().then( router => {

    let mediaHandler = () => {
        if ( window.matchMedia( '(max-width: 767px)' ).matches ) {
            /**
             * Mobile
             */
            Object.defineProperty( Vue.prototype, '$isMobile', {
                value: true,
                writable: false
            } )
        } else {
            /**
             * Desktop
             */
            Object.defineProperty( Vue.prototype, '$isDesktop', {
                value: true,
                writable: false
            } )
        }
        /**
         * To set up a watcher
         */
        // window.matchMedia( '(min-width: 992px)' ).addEventListener( "change", () => {
        // 	console.log( 'changed' )
        // } )
    }

    router.beforeEach( ( to, from, next ) => {
        document.title = to.meta.title
        /**
         * Emit is loading event? Will App component catch it?
         */
        // store.commit( 'setLoading', true )
        next()
    } )

    router.afterEach( ( to, from ) => {
        /**
         * Emit finished loading event?
         */
        // store.commit( 'setLoading', false )
        /**
         * Handle resize based on the browser size
         */
        mediaHandler()
    } )

    Vue.component( 'page-header', PageHeader )

    Vue.mixin( {
        /**
         *
         * @param {RouteObject} to The route we are coming from
         * @param {RouteObject} from The route we are going to
         * @param {RouteResolver} next The function that resolves the route change process next(false) cancels the route change
         *
         * Register a global mixin to triger this event. The event is caught in AdminAppComponent
         * It triggers the route change pre loader
         */
        beforeRouteLeave( to, from, next ) {
            this.$emit( "is-loading" );
            next();
        }
    } )

    axios.get( '/admin-panel/user-instance' ).then( ( {
        data: user
    } ) => {

        Object.defineProperty( Vue.prototype, '$user', {
            value: {
                ...user,
                isAdmin: user.type == 'admin',
                isSalesRep: user.type == 'sales_rep',
            },
            writable: false
        } )

        /* eslint-disable no-new */
        new Vue( {
            el: '#app',
            template: '<App/>',
            components: {
                App
            },
            router,
        } )
    } )



} )
