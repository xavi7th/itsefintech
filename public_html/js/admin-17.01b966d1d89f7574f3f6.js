(window.webpackJsonp=window.webpackJsonp||[]).push([[33],{"2LK/":function(t,a,s){"use strict";s.r(a);var i=s("jbwR");function e(t){return function(t){if(Array.isArray(t)){for(var a=0,s=new Array(t.length);a<t.length;a++)s[a]=t[a];return s}}(t)||function(t){if(Symbol.iterator in Object(t)||"[object Arguments]"===Object.prototype.toString.call(t))return Array.from(t)}(t)||function(){throw new TypeError("Invalid attempt to spread non-iterable instance")}()}var n={name:"AdminDashboard",data:function(){return{statistics:{}}},mounted:function(){this.$emit("page-loaded"),console.log(this.$user),this.initialiseChart()},methods:{initialiseChart:function(){var t=this;axios.get(i.Db).then((function(a){var s=a.data;t.statistics=s,new Morris.Line({element:"purchase-chart",data:e(t.statistics.monthly_summary),xkey:"day",ykeys:["num_of_sales"],labels:["Num of Sales"],lineColors:["#f90a48"],lineWidth:2,grid:!1,axes:!1,gridTextSize:10,padding:5,hideHover:"auto",resize:!0})}))}}},r=(s("Fg6h"),s("KHd+")),d=Object(r.a)(n,(function(){var t=this,a=t.$createElement,s=t._self._c||a;return s("main",[s("page-header",{attrs:{pageTitle:"Dashboard"}}),t._v(" "),s("div",{staticClass:"content"},[s("div",{staticClass:"row"},[s("div",{staticClass:"col-12"},[s("div",{staticClass:"card pb-0"},[t._m(0),t._v(" "),s("div",{staticClass:"card-body pt-0 pb-0"},[s("div",{staticClass:"card-row"},[s("div",{staticClass:"table-responsive"},[s("table",{staticClass:"table table-striped"},[t._m(1),t._v(" "),s("tbody",t._l(t.statistics.sales_rep_sales,(function(a){return s("tr",{key:a.id},[s("td",[t._v(t._s(a.id))]),t._v(" "),s("td",[t._v(t._s(a.customer))]),t._v(" "),s("td",[t._v(t._s(a.phone))]),t._v(" "),s("td",[t._v(t._s(a.payment_method))]),t._v(" "),s("td",[t._v(t._s(a.request_date))]),t._v(" "),s("td",{staticClass:"text-center"},[s("span",{staticClass:"badge badge-dot bodge-lg",class:{"badge-warning":a.is_paid&&!a.is_payment_confirmed,"badge-danger":!a.is_paid,"badge-success":a.is_payment_confirmed}})])])})),0)])])])])])])]),t._v(" "),s("div",{staticClass:"row"},[t._m(2),t._v(" "),s("div",{staticClass:"col-xl-4 col-12"},[s("div",{staticClass:"card"},[t._m(3),t._v(" "),s("div",{staticClass:"card-body py-0"},[s("div",{staticClass:"card-row"},t._l(t.statistics.recent_activities,(function(a){return s("div",{key:a.id,staticClass:"widget-item"},[t._m(4,!0),t._v(" "),s("div",{staticClass:"flex j-c-between w-100p"},[s("span",{staticClass:"widget-title"},[t._v(t._s(a.activity))]),t._v(" "),s("span",{staticClass:"widget-text-small"},[t._v(t._s(a.created_at))])])])})),0)])])])])])],1)}),[function(){var t=this.$createElement,a=this._self._c||t;return a("div",{staticClass:"card-title"},[a("h4",[this._v("SALES LIST")])])},function(){var t=this,a=t.$createElement,s=t._self._c||a;return s("thead",[s("tr",[s("th",{staticClass:"w-10p"},[t._v("ID")]),t._v(" "),s("th",[t._v("Customer")]),t._v(" "),s("th",[t._v("Phone")]),t._v(" "),s("th",[t._v("Payment Method")]),t._v(" "),s("th",[t._v("Date")]),t._v(" "),s("th",{staticClass:"w-10p"},[t._v("Status")])])])},function(){var t=this,a=t.$createElement,s=t._self._c||a;return s("div",{staticClass:"col-xl-8 col-12"},[s("div",{staticClass:"card"},[s("div",{staticClass:"card-body p-0"},[s("div",{staticClass:"pt-30"},[s("h4",{staticClass:"pl-30 mb-20"},[t._v("Orders Summary")]),t._v(" "),s("div",{staticClass:"h-150",attrs:{id:"purchase-chart"}}),t._v(" "),s("div",{staticClass:"flex j-c-between px-30 py-45"},[s("div",[s("span",{staticClass:"fs-16 fw-600 d-block"},[t._v("15")]),t._v(" "),s("small",{staticClass:"fs-14 text-light"},[t._v("Unpaid")])]),t._v(" "),s("div",[s("span",{staticClass:"fs-16 fw-600 d-block"},[t._v("3")]),t._v(" "),s("small",{staticClass:"fs-14 text-light"},[t._v("Unconfirmed")])]),t._v(" "),s("div",[s("span",{staticClass:"fs-16 fw-600 d-block"},[t._v("1")]),t._v(" "),s("small",{staticClass:"fs-14 text-light"},[t._v("Completed")])])])])])])])},function(){var t=this.$createElement,a=this._self._c||t;return a("div",{staticClass:"card-title"},[a("h4",[this._v("NOTIFICATIONS")])])},function(){var t=this.$createElement,a=this._self._c||t;return a("div",{staticClass:"thumbnail-md bg-info mr-20"},[a("i",{staticClass:"fas fa-info"})])}],!1,null,"d938f104",null);a.default=d.exports},Fg6h:function(t,a,s){"use strict";var i=s("XEfD");s.n(i).a},UgfH:function(t,a,s){(t.exports=s("I1BE")(!1)).push([t.i,".card-gradient-dark[data-v-d938f104] {\n  background-image: linear-gradient(to right, #2196f3 30%, #03a9f4 100%);\n}\n.card-gradient-dark small[data-v-d938f104] {\n  font-weight: bold;\n}\n.card-gradient-dark .d-block[data-v-d938f104] {\n  color: #fff;\n}\n.card-gradient small[data-v-d938f104] {\n  font-weight: bold;\n}\n.card-gradient.purple[data-v-d938f104] {\n  background-image: linear-gradient(to right, #3b2169 30%, #673ab7 100%);\n}\n.card-gradient.green[data-v-d938f104] {\n  background-image: linear-gradient(to right, #009688 30%, #4caf50 100%);\n}\n.card-request[data-v-d938f104] {\n  position: absolute;\n  top: 0;\n  right: 0;\n  left: 0;\n  bottom: 0;\n  text-align: center;\n  color: white;\n  font-weight: bold;\n  text-transform: uppercase;\n  cursor: pointer;\n}",""])},XEfD:function(t,a,s){var i=s("UgfH");"string"==typeof i&&(i=[[t.i,i,""]]);var e={hmr:!0,transform:void 0,insertInto:void 0};s("aET+")(i,e);i.locals&&(t.exports=i.locals)}}]);