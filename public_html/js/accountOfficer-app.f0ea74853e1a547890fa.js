(window.webpackJsonp=window.webpackJsonp||[]).push([[2],{0:function(e,t,r){e.exports=r("5FdV")},"5FdV":function(e,t,r){"use strict";r.r(t);r("fbSQ");var n=r("XuX8"),a=r.n(n),o=r("pvlB"),i=r.n(o),c=r("p8Yy"),u=r.n(c),p=r("5L+E"),f=r("8bLC"),s=r("Z7Ao");function l(e,t){var r=Object.keys(e);if(Object.getOwnPropertySymbols){var n=Object.getOwnPropertySymbols(e);t&&(n=n.filter((function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable}))),r.push.apply(r,n)}return r}function b(e){for(var t=1;t<arguments.length;t++){var r=null!=arguments[t]?arguments[t]:{};t%2?l(Object(r),!0).forEach((function(t){d(e,t,r[t])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(r)):l(Object(r)).forEach((function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(r,t))}))}return e}function d(e,t,r){return t in e?Object.defineProperty(e,t,{value:r,enumerable:!0,configurable:!0,writable:!0}):e[t]=r,e}var O=r("CqaE").routeGenerator;a.a.use(u.a),a.a.filter("Naira",(function(e,t){return"₦",a.a.filter("currency")(e,"₦",2,{thousandsSeparator:",",decimalSeparator:"."})})),a.a.use(i.a,{fieldsBagName:"formFields"}),a.a.use(s.a),O().then((function(e){e.beforeEach((function(e,t,r){document.title=e.meta.title,r()})),e.afterEach((function(e,t){window.matchMedia("(max-width: 767px)").matches})),a.a.component("page-header",f.default),a.a.mixin({beforeRouteLeave:function(e,t,r){this.$emit("is-loading"),r()}}),axios.get("/admin-panel/user-instance").then((function(t){var r=t.data;Object.defineProperty(a.a.prototype,"$user",{value:b({},r,{isAccountOfficer:"account_officer"==r.type}),writable:!1}),new a.a({el:"#app",template:"<App/>",components:{App:p.a},router:e})}))}))}},[[0,1,0]]]);