(window.webpackJsonp=window.webpackJsonp||[]).push([[49],{IfJ2:function(t,e,a){(t.exports=a("I1BE")(!1)).push([t.i,'.modal-right .modal-dialog[data-v-f6ac9fa2],\n.modal-left .modal-dialog[data-v-f6ac9fa2] {\n  min-width: 35%;\n}\n.modal-right .modal-dialog .modal-body[data-v-f6ac9fa2],\n.modal-left .modal-dialog .modal-body[data-v-f6ac9fa2] {\n  overflow-y: auto;\n}\n.section-loader[data-v-f6ac9fa2] {\n  min-height: 90vh;\n  margin-left: 0;\n  position: fixed;\n}\n.form-group[data-v-f6ac9fa2] {\n  position: relative;\n}\n.form-group span[data-v-f6ac9fa2] {\n  position: absolute;\n  bottom: 7px;\n  right: 20px;\n  color: #d33;\n  -ms-filter: "progid:DXImageTransform.Microsoft.Alpha(Opacity=0)";\n  opacity: 0;\n  transition: ease-in 300ms opacity;\n  pointer-events: none;\n}\n.form-group.has-error .form-control[data-v-f6ac9fa2] {\n  border-color: #f90a48;\n  box-shadow: 0 0 10px rgba(249, 10, 72, 0.2);\n}\n.form-group.has-error > span[data-v-f6ac9fa2] {\n  -ms-filter: "progid:DXImageTransform.Microsoft.Alpha(Opacity=100)";\n  opacity: 1;\n}',""])},sRhu:function(t,e,a){var s=a("IfJ2");"string"==typeof s&&(s=[[t.i,s,""]]);var i={hmr:!0,transform:void 0,insertInto:void 0};a("aET+")(s,i);s.locals&&(t.exports=s.locals)},t7pG:function(t,e,a){"use strict";var s=a("sRhu");a.n(s).a},zDcu:function(t,e,a){"use strict";a.r(e);var s=a("jbwR");function i(t,e){var a=Object.keys(t);if(Object.getOwnPropertySymbols){var s=Object.getOwnPropertySymbols(t);e&&(s=s.filter((function(e){return Object.getOwnPropertyDescriptor(t,e).enumerable}))),a.push.apply(a,s)}return a}function n(t,e,a){return e in t?Object.defineProperty(t,e,{value:a,enumerable:!0,configurable:!0,writable:!0}):t[e]=a,t}var o={name:"ManageCardAdmins",data:function(){return{users:[],userDetails:{},sectionLoading:!1,all_routes:[],permitted_routes:[],details:{}}},components:{PreLoader:a("YiU+").default},created:function(){var t=this;axios.get(s.ib).then((function(e){var a=e.data.users;t.users=a,t.$isDesktop?t.$nextTick((function(){$((function(){$("#datatable1").DataTable({responsive:!0,scrollX:!1,order:[[0,"desc"]],language:{searchPlaceholder:"Search...",sSearch:""}})}))})):t.$nextTick((function(){$((function(){$("#datatable1").DataTable({responsive:!1,scrollX:!0,order:[[0,"desc"]],language:{searchPlaceholder:"Search...",sSearch:""}})}))}))}))},mounted:function(){this.$emit("page-loaded")},methods:{showModal:function(t){this.userDetails=t},showPermModal:function(t){var e=this;this.userDetails=t,axios.get(Object(s.d)(t.id)).then((function(t){var a=t.data,s=a.all_routes,i=a.permitted_routes;e.all_routes=s,e.permitted_routes=i,e.$nextTick((function(){$((function(){e.sectionLoading=!1}))}))}))},slugToString:function(t){for(var e=t.split("_"),a=0;a<e.length;a++){var s=e[a];e[a]=s.charAt(0).toUpperCase()+s.slice(1)}return e.join(" ")},updateCardAdminPermissions:function(){var t=this;this.sectionLoading=!0,BlockToast.fire({text:"updating account officer's permissions ..."}),axios.put(Object(s.d)(this.userDetails.id),{permitted_routes:this.permitted_routes}).then((function(e){204===e.status?Toast.fire({title:"Success",text:"User permissions updated",position:"center"}):Toast.fire({title:"Failed",text:"Something wrong happend",position:"center"}),t.$nextTick((function(){$((function(){t.sectionLoading=!1}))}))}))},deleteCardAdmin:function(){var t=this;this.sectionLoading=!0,BlockToast.fire({text:"Deleting account officer's account ..."}),axios.delete(Object(s.u)(this.userDetails.id)).then((function(e){204===e.status?Toast.fire({title:"Success",text:"User account deleted",position:"center"}):Toast.fire({title:"Failed",text:"Something wrong happend",position:"center"}),t.$nextTick((function(){$((function(){t.sectionLoading=!1}))}))}))},suspendCardAdmin:function(){var t=this;this.sectionLoading=!0,BlockToast.fire({text:"suspending account officer's account ..."}),axios.put(Object(s.Y)(this.userDetails.id)).then((function(e){204===e.status?Toast.fire({title:"Success",text:"User account suspended",position:"center"}):Toast.fire({title:"Failed",text:"Something wrong happend",position:"center"}),t.$nextTick((function(){$((function(){t.sectionLoading=!1}))}))}))},restoreCardAdmin:function(){var t=this;this.sectionLoading=!0,BlockToast.fire({text:"restoring account officer's account ..."}),axios.put(Object(s.K)(this.userDetails.id)).then((function(e){204===e.status?Toast.fire({title:"Success",text:"User account restored",position:"center"}):Toast.fire({title:"Failed",text:"Something wrong happend",position:"center"}),t.$nextTick((function(){$((function(){t.sectionLoading=!1}))}))}))},createCardAdmin:function(){var t=this;this.$validator.validateAll().then((function(e){e?(BlockToast.fire({text:"Accessing your dashboard..."}),axios.post(s.h,function(t){for(var e=1;e<arguments.length;e++){var a=null!=arguments[e]?arguments[e]:{};e%2?i(Object(a),!0).forEach((function(e){n(t,e,a[e])})):Object.getOwnPropertyDescriptors?Object.defineProperties(t,Object.getOwnPropertyDescriptors(a)):i(Object(a)).forEach((function(e){Object.defineProperty(t,e,Object.getOwnPropertyDescriptor(a,e))}))}return t}({},t.details)).then((function(e){var a=e.status,s=e.data.rsp;console.log(s),void 0!==a&&201==a&&(t.details={},t.users.push(s),Toast.fire({title:"Created",text:"They will be required to set a password om their first login",icon:"success",position:"center"}))})).catch((function(t){500==t.response.status&&swal.fire({title:"Error",text:"Something went wrong on server. Creation not successful.",icon:"error"})}))):Toast.fire({title:"Invalid data! Try again",position:"center",icon:"error"})}))}}},r=(a("t7pG"),a("KHd+")),l=Object(r.a)(o,(function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("main",[a("page-header",{attrs:{pageTitle:"Manage Card Admins"}}),t._v(" "),a("div",{staticClass:"content"},[a("div",{staticClass:"card"},[t._m(0),t._v(" "),a("div",{staticClass:"card-body"},[a("table",{staticClass:"table table-bordered table-hover",attrs:{id:"datatable1"}},[t._m(1),t._v(" "),a("tbody",t._l(t.users,(function(e){return a("tr",{key:e.id},[a("td",[t._v(t._s(e.id))]),t._v(" "),a("td",[t._v(t._s(e.full_name))]),t._v(" "),a("td",[t._v(t._s(e.phone))]),t._v(" "),a("td",[t._v(t._s(e.is_suspended?"Suspended Account":e.is_verified?"Account Verified":"Unverified Account"))]),t._v(" "),a("td",[a("div",{staticClass:"badge badge-success badge-shadow pointer",attrs:{"data-toggle":"modal","data-target":"#modal-details"},on:{click:function(a){return t.showModal(e)}}},[t._v("View Full Details")]),t._v(" "),a("div",{staticClass:"badge badge-purple pointer",attrs:{"data-toggle":"modal","data-target":"#modal-perm"},on:{click:function(a){return t.showPermModal(e)}}},[t._v("Permissions")])])])})),0)]),t._v(" "),a("div",{staticClass:"modal modal-left fade",attrs:{id:"modal-details",tabindex:"-1"}},[a("div",{staticClass:"modal-dialog"},[a("div",{staticClass:"modal-content"},[a("div",{staticClass:"modal-header"},[a("h4",{staticClass:"modal-title"},[t._v(t._s(t.userDetails.full_name)+"' details")]),t._v(" "),t._m(2)]),t._v(" "),a("div",{staticClass:"modal-body"},[a("div",{staticClass:"col-md-12"},[a("div",{staticClass:"card overflow-hidden"},[a("div",{staticClass:"card-body py-0"},[a("div",{staticClass:"card-row"},[a("div",{staticClass:"table-responsive"},[t._m(3),t._v(" "),a("table",{staticClass:"table table-striped"},[t._m(4),t._v(" "),a("tbody",t._l(t.userDetails,(function(e,s,i){return a("tr",{key:i},[a("td",[t._v(t._s(t.slugToString(s)))]),t._v(" "),a("td",["user_passport"!=s?a("span",[t._v(t._s(e))]):a("a",{attrs:{href:e,target:"_blank"}},[a("img",{staticClass:"img-fluid",attrs:{src:e,alt:""}})])])])})),0)])])])])])])]),t._v(" "),a("div",{staticClass:"modal-footer"},[a("button",{staticClass:"btn btn-bold btn-pure btn-secondary",attrs:{type:"button","data-dismiss":"modal"}},[t._v("Close")]),t._v(" "),t.userDetails.is_suspended?a("button",{staticClass:"btn btn-bold btn-pure btn-warning",attrs:{type:"button"},on:{click:t.restoreCardAdmin}},[t._v("Restore Account")]):a("button",{staticClass:"btn btn-bold btn-pure btn-danger",attrs:{type:"button"},on:{click:t.suspendCardAdmin}},[t._v("Suspend Account")])])])])]),t._v(" "),a("div",{staticClass:"modal modal-right fade",attrs:{id:"modal-perm",tabindex:"-1"}},[a("div",{staticClass:"modal-dialog"},[a("div",{staticClass:"modal-content"},[a("div",{staticClass:"modal-header"},[a("h4",{staticClass:"modal-title"},[t._v(t._s(t.userDetails.full_name)+"' details")]),t._v(" "),t._m(5)]),t._v(" "),a("div",{staticClass:"modal-body"},[t.sectionLoading?a("pre-loader",{staticClass:"section-loader"}):t._e(),t._v(" "),a("div",{staticClass:"col-md-12"},[a("div",{staticClass:"card"},[t._m(6),t._v(" "),a("div",{staticClass:"card-body py-0"},[a("div",{staticClass:"card-row"},[t._l(t.all_routes,(function(e){return a("div",{key:e.id,staticClass:"widget-item pt-20 pb-25"},[a("label",{staticClass:"control control-checkbox mb-0"},[a("span",{staticClass:"text-transparent"},[t._v(".")]),t._v(" "),a("input",{directives:[{name:"model",rawName:"v-model",value:t.permitted_routes,expression:"permitted_routes"}],attrs:{type:"checkbox"},domProps:{checked:t.permitted_routes.includes(e.id),value:e.id,checked:Array.isArray(t.permitted_routes)?t._i(t.permitted_routes,e.id)>-1:t.permitted_routes},on:{change:function(a){var s=t.permitted_routes,i=a.target,n=!!i.checked;if(Array.isArray(s)){var o=e.id,r=t._i(s,o);i.checked?r<0&&(t.permitted_routes=s.concat([o])):r>-1&&(t.permitted_routes=s.slice(0,r).concat(s.slice(r+1)))}else t.permitted_routes=n}}}),t._v(" "),a("span",{staticClass:"control-icon"})]),t._v(" "),a("div",{staticClass:"flex j-c-between w-100p"},[a("span",{staticClass:"widget-title mt-2"},[t._v(t._s(e.description))]),t._v(" "),a("span",{staticClass:"widget-text-small"},[t._v(t._s(t.permitted_routes.includes(e.id)?"permitted":"not permitted"))])])])})),t._v(" "),a("button",{staticClass:"btn btn-bold btn-pure btn-info ml-120 my-30",attrs:{type:"button"},on:{click:t.updateCardAdminPermissions}},[t._v("Save changes")])],2)])])])],1),t._v(" "),t._m(7)])])]),t._v(" "),a("div",{staticClass:"modal modal-right fade",attrs:{id:"modal-account-officer",tabindex:"-1"}},[a("div",{staticClass:"modal-dialog"},[a("div",{staticClass:"modal-content"},[t._m(8),t._v(" "),a("div",{staticClass:"modal-body"},[t.sectionLoading?a("pre-loader",{staticClass:"section-loader"}):t._e(),t._v(" "),a("form",{staticClass:"m-25"},[a("div",{staticClass:"form-group mb-5",class:{"has-error":t.errors.has("full_name")}},[t._m(9),t._v(" "),a("input",{directives:[{name:"model",rawName:"v-model",value:t.details.full_name,expression:"details.full_name"},{name:"validate",rawName:"v-validate",value:"required",expression:"'required'"}],staticClass:"form-control form-control-pill",attrs:{type:"text",id:"form-full-name",name:"full_name"},domProps:{value:t.details.full_name},on:{input:function(e){e.target.composing||t.$set(t.details,"full_name",e.target.value)}}}),t._v(" "),a("span",[t._v(t._s(t.errors.first("full_name")))])]),t._v(" "),a("div",{staticClass:"form-group mb-5",class:{"has-error":t.errors.has("email")}},[t._m(10),t._v(" "),a("input",{directives:[{name:"model",rawName:"v-model",value:t.details.email,expression:"details.email"},{name:"validate",rawName:"v-validate",value:"required|email",expression:"'required|email'"}],staticClass:"form-control form-control-pill",attrs:{type:"text",id:"form-mail",name:"email"},domProps:{value:t.details.email},on:{input:function(e){e.target.composing||t.$set(t.details,"email",e.target.value)}}}),t._v(" "),a("span",[t._v(t._s(t.errors.first("email")))])]),t._v(" "),a("div",{staticClass:"form-group mb-5",class:{"has-error":t.errors.has("phone")}},[t._m(11),t._v(" "),a("input",{directives:[{name:"model",rawName:"v-model",value:t.details.phone,expression:"details.phone"},{name:"validate",rawName:"v-validate",value:"required",expression:"'required'"}],staticClass:"form-control form-control-pill",attrs:{type:"text",id:"form-phone",name:"phone"},domProps:{value:t.details.phone},on:{input:function(e){e.target.composing||t.$set(t.details,"phone",e.target.value)}}}),t._v(" "),a("span",[t._v(t._s(t.errors.first("phone")))])]),t._v(" "),a("div",{staticClass:"form-group mt-20"},[a("button",{staticClass:"btn btn-rss btn-round btn-block",attrs:{type:"button","data-dismiss":"modal"},on:{click:t.createCardAdmin}},[t._v("Create")])])])],1),t._v(" "),t._m(12)])])])])])])],1)}),[function(){var t=this.$createElement,e=this._self._c||t;return e("div",{staticClass:"card-title"},[e("button",{staticClass:"btn btn-bold btn-pure btn-twitter btn-shadow",attrs:{type:"button","data-toggle":"modal","data-target":"#modal-account-officer"}},[this._v("Create Card Admin")])])},function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("thead",[a("tr",[a("th",[t._v("ID")]),t._v(" "),a("th",[t._v("Full Name")]),t._v(" "),a("th",[t._v("Phone")]),t._v(" "),a("th",[t._v("Status")]),t._v(" "),a("th",[t._v("Actions")])])])},function(){var t=this.$createElement,e=this._self._c||t;return e("button",{staticClass:"close",attrs:{type:"button","data-dismiss":"modal"}},[e("span",{attrs:{"aria-hidden":"true"}},[this._v("×")])])},function(){var t=this.$createElement,e=this._self._c||t;return e("div",{staticClass:"flex j-c-between bd bg-light py-30 px-30"},[e("div",{staticClass:"flex-sh-0 ln-18"},[e("div",{staticClass:"fs-16 fw-500 text-success"},[this._v("Card Admin")]),this._v(" "),e("span",{staticClass:"fs-12 text-light"},[this._v("User Role")])])])},function(){var t=this.$createElement,e=this._self._c||t;return e("thead",[e("tr",[e("th",[this._v("Field")]),this._v(" "),e("th",[this._v("Value")])])])},function(){var t=this.$createElement,e=this._self._c||t;return e("button",{staticClass:"close",attrs:{type:"button","data-dismiss":"modal"}},[e("span",{attrs:{"aria-hidden":"true"}},[this._v("×")])])},function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",{staticClass:"card-title flex j-c-between"},[a("h4",[t._v("TASKS")]),t._v(" "),a("div",{staticClass:"dropdown"},[a("a",{staticClass:"btn-dot-link",attrs:{href:"","data-toggle":"dropdown"}},[a("span"),t._v(" "),a("span"),t._v(" "),a("span")]),t._v(" "),a("div",{staticClass:"dropdown-menu dropdown-menu-right fs-12"},[a("a",{staticClass:"dropdown-item fs-12",attrs:{href:""}},[a("i",{staticClass:"fa fa-plus w-20"}),t._v(" Add New\n                          ")]),t._v(" "),a("a",{staticClass:"dropdown-item fs-12",attrs:{href:""}},[a("i",{staticClass:"fas fa-redo-alt w-20"}),t._v(" Refresh\n                          ")])])])])},function(){var t=this.$createElement,e=this._self._c||t;return e("div",{staticClass:"modal-footer"},[e("button",{staticClass:"btn btn-bold btn-pure btn-secondary",attrs:{type:"button","data-dismiss":"modal"}},[this._v("Close")])])},function(){var t=this.$createElement,e=this._self._c||t;return e("div",{staticClass:"modal-header"},[e("h4",{staticClass:"modal-title"},[this._v("Enter Card Admin Details")]),this._v(" "),e("button",{staticClass:"close",attrs:{type:"button","data-dismiss":"modal"}},[e("span",{attrs:{"aria-hidden":"true"}},[this._v("×")])])])},function(){var t=this.$createElement,e=this._self._c||t;return e("label",{attrs:{for:"form-full-name"}},[e("strong",[this._v("Full Name")])])},function(){var t=this.$createElement,e=this._self._c||t;return e("label",{attrs:{for:"form-mail"}},[e("strong",[this._v("E-Mail")])])},function(){var t=this.$createElement,e=this._self._c||t;return e("label",{attrs:{for:"form-phone"}},[e("strong",[this._v("Phone")])])},function(){var t=this.$createElement,e=this._self._c||t;return e("div",{staticClass:"modal-footer"},[e("button",{staticClass:"btn btn-bold btn-pure btn-secondary",attrs:{type:"button","data-dismiss":"modal"}},[this._v("Close")])])}],!1,null,"f6ac9fa2",null);e.default=l.exports}}]);