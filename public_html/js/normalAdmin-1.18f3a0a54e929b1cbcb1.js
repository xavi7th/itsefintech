(window.webpackJsonp=window.webpackJsonp||[]).push([[63],{BfNQ:function(t,e,s){"use strict";var a=s("UryG");s.n(a).a},UryG:function(t,e,s){var a=s("wRM5");"string"==typeof a&&(a=[[t.i,a,""]]);var i={hmr:!0,transform:void 0,insertInto:void 0};s("aET+")(a,i);a.locals&&(t.exports=a.locals)},VqLQ:function(t,e,s){"use strict";s.r(e);var a=s("jbwR");function i(t,e){var s=Object.keys(t);if(Object.getOwnPropertySymbols){var a=Object.getOwnPropertySymbols(t);e&&(a=a.filter((function(e){return Object.getOwnPropertyDescriptor(t,e).enumerable}))),s.push.apply(s,a)}return s}function n(t,e,s){return e in t?Object.defineProperty(t,e,{value:s,enumerable:!0,configurable:!0,writable:!0}):t[e]=s,t}var o={name:"ManageAdmins",data:function(){return{users:[],userDetails:{},sectionLoading:!1,all_routes:[],permitted_routes:[],details:{}}},components:{PreLoader:s("YiU+").default},created:function(){var t=this;axios.get(a.hb).then((function(e){var s=e.data.users;t.users=s,t.$isDesktop?t.$nextTick((function(){$((function(){$("#datatable1").DataTable({responsive:!0,scrollX:!1,order:[[0,"desc"]],language:{searchPlaceholder:"Search...",sSearch:""}})}))})):t.$nextTick((function(){$((function(){$("#datatable1").DataTable({responsive:!1,scrollX:!0,order:[[0,"desc"]],language:{searchPlaceholder:"Search...",sSearch:""}})}))}))}))},mounted:function(){this.$emit("page-loaded")},methods:{showModal:function(t){this.userDetails=t},showPermModal:function(t){var e=this;this.userDetails=t,axios.get(Object(a.c)(t.id)).then((function(t){var s=t.data,a=s.all_routes,i=s.permitted_routes;e.all_routes=a,e.permitted_routes=i,e.$nextTick((function(){$((function(){e.sectionLoading=!1}))}))}))},slugToString:function(t){for(var e=t.split("_"),s=0;s<e.length;s++){var a=e[s];e[s]=a.charAt(0).toUpperCase()+a.slice(1)}return e.join(" ")},updateAdminPermissions:function(){var t=this;this.sectionLoading=!0,BlockToast.fire({text:"updating admin permissions ..."}),axios.put(Object(a.c)(this.userDetails.id),{permitted_routes:this.permitted_routes}).then((function(e){204===e.status?Toast.fire({title:"Success",text:"User permissions updated",position:"center"}):Toast.fire({title:"Failed",text:"Something wrong happend",position:"center"}),t.$nextTick((function(){$((function(){t.sectionLoading=!1}))}))}))},deleteAdmin:function(){var t=this;this.sectionLoading=!0,BlockToast.fire({text:"Deleting admin account ..."}),axios.delete(Object(a.t)(this.userDetails.id)).then((function(e){204===e.status?Toast.fire({title:"Success",text:"User account deleted",position:"center"}):Toast.fire({title:"Failed",text:"Something wrong happend",position:"center"}),t.$nextTick((function(){$((function(){t.sectionLoading=!1}))}))}))},suspendAdmin:function(){var t=this;this.sectionLoading=!0,BlockToast.fire({text:"suspending admin account ..."}),axios.put(Object(a.X)(this.userDetails.id)).then((function(e){204===e.status?Toast.fire({title:"Success",text:"User account suspended",position:"center"}):Toast.fire({title:"Failed",text:"Something wrong happend",position:"center"}),t.$nextTick((function(){$((function(){t.sectionLoading=!1}))}))}))},restoreAdmin:function(){var t=this;this.sectionLoading=!0,BlockToast.fire({text:"restore admin account ..."}),axios.put(Object(a.J)(this.userDetails.id)).then((function(e){204===e.status?Toast.fire({title:"Success",text:"User account restored",position:"center"}):Toast.fire({title:"Failed",text:"Something wrong happend",position:"center"}),t.$nextTick((function(){$((function(){t.sectionLoading=!1}))}))}))},createAdmin:function(){var t=this;this.$validator.validateAll().then((function(e){e?(BlockToast.fire({text:"Accessing your dashboard..."}),axios.post(a.g,function(t){for(var e=1;e<arguments.length;e++){var s=null!=arguments[e]?arguments[e]:{};e%2?i(Object(s),!0).forEach((function(e){n(t,e,s[e])})):Object.getOwnPropertyDescriptors?Object.defineProperties(t,Object.getOwnPropertyDescriptors(s)):i(Object(s)).forEach((function(e){Object.defineProperty(t,e,Object.getOwnPropertyDescriptor(s,e))}))}return t}({},t.details)).then((function(e){var s=e.status,a=e.data.rsp;console.log(a),void 0!==s&&201==s&&(t.details={},t.users.push(a),Toast.fire({title:"Created",text:"They will be required to set a password om their first login",icon:"success",position:"center"}))})).catch((function(t){500==t.response.status&&swal.fire({title:"Error",text:"Something went wrong on server. Creation not successful.",icon:"error"})}))):Toast.fire({title:"Invalid data! Try again",position:"center",icon:"error"})}))}}},r=(s("BfNQ"),s("KHd+")),l=Object(r.a)(o,(function(){var t=this,e=t.$createElement,s=t._self._c||e;return s("main",[s("page-header",{attrs:{pageTitle:"Manage Admins"}}),t._v(" "),s("div",{staticClass:"content"},[s("div",{staticClass:"card"},[t._m(0),t._v(" "),s("div",{staticClass:"card-body"},[s("table",{staticClass:"table table-bordered table-hover",attrs:{id:"datatable1"}},[t._m(1),t._v(" "),s("tbody",t._l(t.users,(function(e){return s("tr",{key:e.id},[s("td",[t._v(t._s(e.id))]),t._v(" "),s("td",[t._v(t._s(e.full_name))]),t._v(" "),s("td",[t._v(t._s(e.phone))]),t._v(" "),s("td",[t._v(t._s(e.is_suspended?"Suspended Account":e.is_verified?"Account Verified":"Unverified Account"))]),t._v(" "),s("td",[s("div",{staticClass:"badge badge-success badge-shadow pointer",attrs:{"data-toggle":"modal","data-target":"#modal-details"},on:{click:function(s){return t.showModal(e)}}},[t._v("View Full Details")]),t._v(" "),s("div",{staticClass:"badge badge-purple pointer",attrs:{"data-toggle":"modal","data-target":"#modal-perm"},on:{click:function(s){return t.showPermModal(e)}}},[t._v("Permissions")])])])})),0)]),t._v(" "),s("div",{staticClass:"modal modal-left fade",attrs:{id:"modal-details",tabindex:"-1"}},[s("div",{staticClass:"modal-dialog"},[s("div",{staticClass:"modal-content"},[s("div",{staticClass:"modal-header"},[s("h4",{staticClass:"modal-title"},[t._v(t._s(t.userDetails.full_name)+"' details")]),t._v(" "),t._m(2)]),t._v(" "),s("div",{staticClass:"modal-body"},[s("div",{staticClass:"col-md-12"},[s("div",{staticClass:"card overflow-hidden"},[s("div",{staticClass:"card-body py-0"},[s("div",{staticClass:"card-row"},[s("div",{staticClass:"table-responsive"},[t._m(3),t._v(" "),s("table",{staticClass:"table table-striped"},[t._m(4),t._v(" "),s("tbody",t._l(t.userDetails,(function(e,a,i){return s("tr",{key:i},[s("td",[t._v(t._s(t.slugToString(a)))]),t._v(" "),s("td",["user_passport"!=a?s("span",[t._v(t._s(e))]):s("a",{attrs:{href:e,target:"_blank"}},[s("img",{staticClass:"img-fluid",attrs:{src:e,alt:""}})])])])})),0)])])])])])])]),t._v(" "),s("div",{staticClass:"modal-footer"},[s("button",{staticClass:"btn btn-bold btn-pure btn-secondary",attrs:{type:"button","data-dismiss":"modal"}},[t._v("Close")]),t._v(" "),t.userDetails.is_suspended?s("button",{staticClass:"btn btn-bold btn-pure btn-warning",attrs:{type:"button"},on:{click:t.restoreAdmin}},[t._v("Restore Account")]):s("button",{staticClass:"btn btn-bold btn-pure btn-danger",attrs:{type:"button"},on:{click:t.suspendAdmin}},[t._v("Suspend Account")])])])])]),t._v(" "),s("div",{staticClass:"modal modal-right fade",attrs:{id:"modal-perm",tabindex:"-1"}},[s("div",{staticClass:"modal-dialog"},[s("div",{staticClass:"modal-content"},[s("div",{staticClass:"modal-header"},[s("h4",{staticClass:"modal-title"},[t._v(t._s(t.userDetails.full_name)+"' details")]),t._v(" "),t._m(5)]),t._v(" "),s("div",{staticClass:"modal-body"},[t.sectionLoading?s("pre-loader",{staticClass:"section-loader"}):t._e(),t._v(" "),s("div",{staticClass:"col-md-12"},[s("div",{staticClass:"card"},[t._m(6),t._v(" "),s("div",{staticClass:"card-body py-0"},[s("div",{staticClass:"card-row"},[t._l(t.all_routes,(function(e){return s("div",{key:e.id,staticClass:"widget-item pt-20 pb-25"},[s("label",{staticClass:"control control-checkbox mb-0"},[s("span",{staticClass:"text-transparent"},[t._v(".")]),t._v(" "),s("input",{directives:[{name:"model",rawName:"v-model",value:t.permitted_routes,expression:"permitted_routes"}],attrs:{type:"checkbox"},domProps:{checked:t.permitted_routes.includes(e.id),value:e.id,checked:Array.isArray(t.permitted_routes)?t._i(t.permitted_routes,e.id)>-1:t.permitted_routes},on:{change:function(s){var a=t.permitted_routes,i=s.target,n=!!i.checked;if(Array.isArray(a)){var o=e.id,r=t._i(a,o);i.checked?r<0&&(t.permitted_routes=a.concat([o])):r>-1&&(t.permitted_routes=a.slice(0,r).concat(a.slice(r+1)))}else t.permitted_routes=n}}}),t._v(" "),s("span",{staticClass:"control-icon"})]),t._v(" "),s("div",{staticClass:"flex j-c-between w-100p"},[s("span",{staticClass:"widget-title mt-2"},[t._v(t._s(e.description))]),t._v(" "),s("span",{staticClass:"widget-text-small"},[t._v(t._s(t.permitted_routes.includes(e.id)?"permitted":"not permitted"))])])])})),t._v(" "),s("button",{staticClass:"btn btn-bold btn-pure btn-info ml-120 my-30",attrs:{type:"button"},on:{click:t.updateAdminPermissions}},[t._v("Save changes")])],2)])])])],1),t._v(" "),t._m(7)])])]),t._v(" "),s("div",{staticClass:"modal modal-right fade",attrs:{id:"modal-admin",tabindex:"-1"}},[s("div",{staticClass:"modal-dialog"},[s("div",{staticClass:"modal-content"},[t._m(8),t._v(" "),s("div",{staticClass:"modal-body"},[t.sectionLoading?s("pre-loader",{staticClass:"section-loader"}):t._e(),t._v(" "),s("form",{staticClass:"m-25"},[s("div",{staticClass:"form-group mb-5",class:{"has-error":t.errors.has("full_name")}},[t._m(9),t._v(" "),s("input",{directives:[{name:"model",rawName:"v-model",value:t.details.full_name,expression:"details.full_name"},{name:"validate",rawName:"v-validate",value:"required",expression:"'required'"}],staticClass:"form-control form-control-pill",attrs:{type:"text",id:"form-full-name",name:"full_name"},domProps:{value:t.details.full_name},on:{input:function(e){e.target.composing||t.$set(t.details,"full_name",e.target.value)}}}),t._v(" "),s("span",[t._v(t._s(t.errors.first("full_name")))])]),t._v(" "),s("div",{staticClass:"form-group mb-5",class:{"has-error":t.errors.has("email")}},[t._m(10),t._v(" "),s("input",{directives:[{name:"model",rawName:"v-model",value:t.details.email,expression:"details.email"},{name:"validate",rawName:"v-validate",value:"required|email",expression:"'required|email'"}],staticClass:"form-control form-control-pill",attrs:{type:"text",id:"form-mail",name:"email"},domProps:{value:t.details.email},on:{input:function(e){e.target.composing||t.$set(t.details,"email",e.target.value)}}}),t._v(" "),s("span",[t._v(t._s(t.errors.first("email")))])]),t._v(" "),s("div",{staticClass:"form-group mb-5",class:{"has-error":t.errors.has("phone")}},[t._m(11),t._v(" "),s("input",{directives:[{name:"model",rawName:"v-model",value:t.details.phone,expression:"details.phone"},{name:"validate",rawName:"v-validate",value:"required",expression:"'required'"}],staticClass:"form-control form-control-pill",attrs:{type:"text",id:"form-phone",name:"phone"},domProps:{value:t.details.phone},on:{input:function(e){e.target.composing||t.$set(t.details,"phone",e.target.value)}}}),t._v(" "),s("span",[t._v(t._s(t.errors.first("phone")))])]),t._v(" "),s("div",{staticClass:"form-group mt-20"},[s("button",{staticClass:"btn btn-rss btn-round btn-block",attrs:{type:"button","data-dismiss":"modal"},on:{click:t.createAdmin}},[t._v("Create")])])])],1),t._v(" "),t._m(12)])])])])])])],1)}),[function(){var t=this.$createElement,e=this._self._c||t;return e("div",{staticClass:"card-title"},[e("button",{staticClass:"btn btn-bold btn-pure btn-twitter btn-shadow",attrs:{type:"button","data-toggle":"modal","data-target":"#modal-admin"}},[this._v("Create Admin")])])},function(){var t=this,e=t.$createElement,s=t._self._c||e;return s("thead",[s("tr",[s("th",[t._v("ID")]),t._v(" "),s("th",[t._v("Full Name")]),t._v(" "),s("th",[t._v("Phone")]),t._v(" "),s("th",[t._v("Status")]),t._v(" "),s("th",[t._v("Actions")])])])},function(){var t=this.$createElement,e=this._self._c||t;return e("button",{staticClass:"close",attrs:{type:"button","data-dismiss":"modal"}},[e("span",{attrs:{"aria-hidden":"true"}},[this._v("×")])])},function(){var t=this.$createElement,e=this._self._c||t;return e("div",{staticClass:"flex j-c-between bd bg-light py-30 px-30"},[e("div",{staticClass:"flex-sh-0 ln-18"},[e("div",{staticClass:"fs-16 fw-500 text-success"},[this._v("Agent")]),this._v(" "),e("span",{staticClass:"fs-12 text-light"},[this._v("User Role")])])])},function(){var t=this.$createElement,e=this._self._c||t;return e("thead",[e("tr",[e("th",[this._v("Field")]),this._v(" "),e("th",[this._v("Value")])])])},function(){var t=this.$createElement,e=this._self._c||t;return e("button",{staticClass:"close",attrs:{type:"button","data-dismiss":"modal"}},[e("span",{attrs:{"aria-hidden":"true"}},[this._v("×")])])},function(){var t=this,e=t.$createElement,s=t._self._c||e;return s("div",{staticClass:"card-title flex j-c-between"},[s("h4",[t._v("TASKS")]),t._v(" "),s("div",{staticClass:"dropdown"},[s("a",{staticClass:"btn-dot-link",attrs:{href:"","data-toggle":"dropdown"}},[s("span"),t._v(" "),s("span"),t._v(" "),s("span")]),t._v(" "),s("div",{staticClass:"dropdown-menu dropdown-menu-right fs-12"},[s("a",{staticClass:"dropdown-item fs-12",attrs:{href:""}},[s("i",{staticClass:"fa fa-plus w-20"}),t._v(" Add New\n                          ")]),t._v(" "),s("a",{staticClass:"dropdown-item fs-12",attrs:{href:""}},[s("i",{staticClass:"fas fa-redo-alt w-20"}),t._v(" Refresh\n                          ")])])])])},function(){var t=this.$createElement,e=this._self._c||t;return e("div",{staticClass:"modal-footer"},[e("button",{staticClass:"btn btn-bold btn-pure btn-secondary",attrs:{type:"button","data-dismiss":"modal"}},[this._v("Close")])])},function(){var t=this.$createElement,e=this._self._c||t;return e("div",{staticClass:"modal-header"},[e("h4",{staticClass:"modal-title"},[this._v("Enter Admin Details")]),this._v(" "),e("button",{staticClass:"close",attrs:{type:"button","data-dismiss":"modal"}},[e("span",{attrs:{"aria-hidden":"true"}},[this._v("×")])])])},function(){var t=this.$createElement,e=this._self._c||t;return e("label",{attrs:{for:"form-full-name"}},[e("strong",[this._v("Full Name")])])},function(){var t=this.$createElement,e=this._self._c||t;return e("label",{attrs:{for:"form-mail"}},[e("strong",[this._v("E-Mail")])])},function(){var t=this.$createElement,e=this._self._c||t;return e("label",{attrs:{for:"form-phone"}},[e("strong",[this._v("Phone")])])},function(){var t=this.$createElement,e=this._self._c||t;return e("div",{staticClass:"modal-footer"},[e("button",{staticClass:"btn btn-bold btn-pure btn-secondary",attrs:{type:"button","data-dismiss":"modal"}},[this._v("Close")])])}],!1,null,"13cc1def",null);e.default=l.exports},wRM5:function(t,e,s){(t.exports=s("I1BE")(!1)).push([t.i,'.modal-right .modal-dialog[data-v-13cc1def],\n.modal-left .modal-dialog[data-v-13cc1def] {\n  min-width: 35%;\n}\n.modal-right .modal-dialog .modal-body[data-v-13cc1def],\n.modal-left .modal-dialog .modal-body[data-v-13cc1def] {\n  overflow-y: auto;\n}\n.section-loader[data-v-13cc1def] {\n  min-height: 90vh;\n  margin-left: 0;\n  position: fixed;\n}\n.form-group[data-v-13cc1def] {\n  position: relative;\n}\n.form-group span[data-v-13cc1def] {\n  position: absolute;\n  bottom: 7px;\n  right: 20px;\n  color: #d33;\n  -ms-filter: "progid:DXImageTransform.Microsoft.Alpha(Opacity=0)";\n  opacity: 0;\n  transition: ease-in 300ms opacity;\n  pointer-events: none;\n}\n.form-group.has-error .form-control[data-v-13cc1def] {\n  border-color: #f90a48;\n  box-shadow: 0 0 10px rgba(249, 10, 72, 0.2);\n}\n.form-group.has-error > span[data-v-13cc1def] {\n  -ms-filter: "progid:DXImageTransform.Microsoft.Alpha(Opacity=100)";\n  opacity: 1;\n}',""])}}]);