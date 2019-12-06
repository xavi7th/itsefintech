import '@basicsite-assets/js/bootstrap'
import Vue from 'vue'
import VeeValidate from 'vee-validate'
import App from './AdminAppComponent'
import PageHeader from "@admin-components/partials/PageHeaderComponent";
// import Vue2Filters from 'vue2-filters'
const {
    routeGenerator
} = require( './router' )

import LoadScript from 'vue-plugin-load-script'



// Vue.use( Vue2Filters )
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
            // console.log( 'mobile view' );
        } else {
            /**
             * Desktop
             */
            // console.log( 'desktop view' );
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
