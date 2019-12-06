<template>
  <main>
    <page-header pageTitle="Manage Admins"></page-header>
    <div class="content">
      <!-- table basic -->
      <div class="card">
        <div class="card-title">
          <button
            type="button"
            class="btn btn-bold btn-pure btn-twitter btn-shadow"
            data-toggle="modal"
            data-target="#modal-admin"
          >Create Admin</button>
        </div>
        <div class="card-body">
          <table class="table table-bordered table-hover" id="datatable1">
            <thead>
              <tr>
                <th>ID</th>
                <th>Full Name</th>
                <th>Phone</th>
                <th>Status</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="user in users" :key="user.id">
                <td>{{ user.id }}</td>
                <td>{{ user.full_name }}</td>
                <td>{{ user.phone }}</td>
                <td>{{ user.is_verified ? 'Account Verified' : 'Unverified Account' }}</td>
                <td>
                  <div
                    class="badge badge-success badge-shadow pointer"
                    data-toggle="modal"
                    data-target="#modal-right"
                    @click="showModal(user)"
                  >View Full Details</div>
                  <div
                    class="badge badge-purple pointer"
                    data-toggle="modal"
                    data-target="#modal-perm"
                    @click="showPermModal(user)"
                  >Permissions</div>
                </td>
              </tr>
            </tbody>
          </table>

          <div class="modal modal-left fade" id="modal-right" tabindex="-1">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h4 class="modal-title">{{ userDetails.full_name }}' details</h4>
                  <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body">
                  <div class="col-md-12">
                    <div class="card overflow-hidden">
                      <div class="card-body py-0">
                        <div class="card-row">
                          <div class="table-responsive">
                            <!-- <div class="flex j-c-between bd bg-light py-30 px-30">
                                <div class="flex-sh-0 ln-18">
                                  <div class="fs-16 fw-500 text-success">122 Sales</div>
                                  <span class="fs-12 text-light">
                                    <i class="far fa-clock"></i> 24 hours
                                  </span>
                                </div>
                                <div class="flex-sh-0 ln-18">
                                  <div class="fs-16 fw-500 text-danger">3 Problem</div>
                                  <span class="fs-12 text-light">
                                    <i class="far fa-clock"></i> 24 hours
                                  </span>
                                </div>
                                <div class="flex-sh-0 ln-18">
                                  <div class="fs-16 fw-500 text-warning">14 Waiting</div>
                                  <span class="fs-12 text-light">
                                    <i class="far fa-clock"></i> 24 hours
                                  </span>
                                </div>
                            </div>-->
                            <table class="table table-striped">
                              <thead>
                                <tr>
                                  <th>Field</th>
                                  <th>Value</th>
                                </tr>
                              </thead>
                              <tbody>
                                <tr v-for="(value, property, idx) in userDetails" :key="idx">
                                  <td>{{ slugToString(property) }}</td>
                                  <td>
                                    <span v-if="property != 'user_passport'">{{ value }}</span>
                                    <a :href="value" v-else target="_blank">
                                      <img :src="value" alt class="img-fluid" />
                                    </a>
                                  </td>
                                </tr>
                              </tbody>
                            </table>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="modal-footer">
                  <button
                    type="button"
                    class="btn btn-bold btn-pure btn-secondary"
                    data-dismiss="modal"
                  >Close</button>
                  <!-- <button type="button" class="btn btn-bold btn-pure btn-primary">Save changes</button> -->
                </div>
              </div>
            </div>
          </div>

          <div class="modal modal-right fade" id="modal-perm" tabindex="-1">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h4 class="modal-title">{{ userDetails.full_name }}' details</h4>
                  <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body">
                  <pre-loader v-if="sectionLoading" class="section-loader"></pre-loader>
                  <div class="col-md-12">
                    <div class="card">
                      <div class="card-title flex j-c-between">
                        <h4>TASKS</h4>
                        <div class="dropdown">
                          <a href class="btn-dot-link" data-toggle="dropdown">
                            <span></span>
                            <span></span>
                            <span></span>
                          </a>
                          <div class="dropdown-menu dropdown-menu-right fs-12">
                            <a href class="dropdown-item fs-12">
                              <i class="fa fa-plus w-20"></i> Add New
                            </a>
                            <a href class="dropdown-item fs-12">
                              <i class="fas fa-redo-alt w-20"></i> Refresh
                            </a>
                          </div>
                        </div>
                      </div>

                      <div class="card-body py-0">
                        <div class="card-row">
                          <div
                            class="widget-item pt-20 pb-25"
                            v-for="value in all_routes"
                            :key="value.id"
                          >
                            <label class="control control-checkbox mb-0">
                              <span class="text-transparent">.</span>
                              <input
                                type="checkbox"
                                :checked="permitted_routes.includes(value.id)"
                                v-model="permitted_routes"
                                :value="value.id"
                              />
                              <span class="control-icon"></span>
                            </label>
                            <div class="flex j-c-between w-100p">
                              <span class="widget-title mt-2">{{ value.description }}</span>
                              <span
                                class="widget-text-small"
                              >{{ permitted_routes.includes(value.id) ? 'permitted' : 'not permitted' }}</span>
                            </div>
                          </div>
                          <button
                            type="button"
                            class="btn btn-bold btn-pure btn-info ml-120 my-30"
                            @click="updateAdminPermissions"
                          >Save changes</button>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="modal-footer">
                  <button
                    type="button"
                    class="btn btn-bold btn-pure btn-secondary"
                    data-dismiss="modal"
                  >Close</button>
                </div>
              </div>
            </div>
          </div>

          <div class="modal modal-right fade" id="modal-admin" tabindex="-1">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h4 class="modal-title">Enter Admin Details</h4>
                  <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body">
                  <pre-loader v-if="sectionLoading" class="section-loader"></pre-loader>
                  <form class="m-25">
                    <div class="form-group mb-5" :class="{'has-error': errors.has('full_name')}">
                      <label for="form-full-name">
                        <strong>Full Name</strong>
                      </label>
                      <input
                        type="text"
                        class="form-control form-control-pill"
                        id="form-full-name"
                        v-model="details.full_name"
                        v-validate="'required'"
                        name="full_name"
                      />
                      <span>{{ errors.first('full_name') }}</span>
                    </div>
                    <div class="form-group mb-5" :class="{'has-error': errors.has('email')}">
                      <label for="form-mail">
                        <strong>E-Mail</strong>
                      </label>
                      <input
                        type="text"
                        class="form-control form-control-pill"
                        id="form-mail"
                        v-model="details.email"
                        v-validate="'required|email'"
                        name="email"
                      />
                      <span>{{ errors.first('email') }}</span>
                    </div>
                    <div class="form-group mb-5" :class="{'has-error': errors.has('phone')}">
                      <label for="form-phone">
                        <strong>Phone</strong>
                      </label>
                      <input
                        type="text"
                        class="form-control form-control-pill"
                        id="form-phone"
                        v-model="details.phone"
                        v-validate="'required'"
                        name="phone"
                      />
                      <span>{{ errors.first('phone') }}</span>
                    </div>

                    <div class="form-group mt-20">
                      <button
                        type="button"
                        class="btn btn-rss btn-round btn-block"
                        data-dismiss="modal"
                        @click="createAdmin"
                      >Create</button>
                    </div>
                  </form>
                </div>
                <div class="modal-footer">
                  <button
                    type="button"
                    class="btn btn-bold btn-pure btn-secondary"
                    data-dismiss="modal"
                  >Close</button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>
</template>

<script>
  import {
    adminViewAdmins,
    adminAdminPermissions,
    adminCreateAdmin
  } from "@admin-assets/js/config";
  import PreLoader from "@admin-components/misc/PageLoader";
  export default {
    name: "ManageAdmins",
    data: () => ({
      users: [],
      userDetails: {},
      sectionLoading: false,
      all_routes: [],
      permitted_routes: [],
      details: {}
    }),
    components: {
      PreLoader
    },
    created() {
      axios.get(adminViewAdmins).then(({ data: { users } }) => {
        this.users = users;

        this.$nextTick(() => {
          $(function() {
            $("#datatable1").DataTable({
              responsive: true,
              language: {
                searchPlaceholder: "Search...",
                sSearch: ""
              }
            });
          });
        });
      });
    },
    mounted() {
      this.$emit("page-loaded");
    },
    methods: {
      showModal(userDetails) {
        this.userDetails = userDetails;
      },
      showPermModal(userDetails) {
        this.userDetails = userDetails;
        axios
          .get(adminAdminPermissions(userDetails.id))
          .then(({ data: { all_routes, permitted_routes } }) => {
            this.all_routes = all_routes;
            this.permitted_routes = permitted_routes;

            this.$nextTick(() => {
              $(() => {
                this.sectionLoading = false;
              });
            });
          });
      },
      slugToString(slug) {
        let words = slug.split("_");

        for (let i = 0; i < words.length; i++) {
          let word = words[i];
          words[i] = word.charAt(0).toUpperCase() + word.slice(1);
        }
        return words.join(" ");
      },
      updateAdminPermissions() {
        this.sectionLoading = true;
        BlockToast.fire({
          text: "updating admin permissions ..."
        });
        axios
          .put(adminAdminPermissions(this.userDetails.id), {
            permitted_routes: this.permitted_routes
          })
          .then(({ status }) => {
            if (status === 204) {
              Toast.fire({
                title: "Success",
                text: "User permissions updated",
                position: "center"
              });
            } else {
              Toast.fire({
                title: "Failed",
                text: "Something wrong happend",
                position: "center"
              });
            }

            this.$nextTick(() => {
              $(() => {
                this.sectionLoading = false;
              });
            });
          });
      },
      createAdmin() {
        this.$validator.validateAll().then(result => {
          if (!result) {
            Toast.fire({
              title: "Invalid data! Try again",
              position: "center",
              type: "error"
            });
          } else {
            BlockToast.fire({
              text: "Accessing your dashboard..."
            });

            axios
              .post(adminCreateAdmin, { ...this.details })
              .then(({ status, data: { rsp } }) => {
                console.log(rsp);

                if (undefined !== status && status == 201) {
                  this.details = {};
                  this.users.push(rsp);
                  Toast.fire({
                    title: "Created",
                    text: `They will be required to set a password om their first login`,
                    type: "success",
                    position: "center"
                  });
                }
              })
              .catch(err => {
                if (err.response.status == 500) {
                  swal.fire({
                    title: "Error",
                    text: `Something went wrong on server. Creation not successful.`,
                    type: "error"
                  });
                }
              });
          }
        });
      }
    }
  };
</script>

<style lang="scss" scoped>
  .modal-right {
    .modal-dialog {
      min-width: 35%;

      .modal-body {
        overflow-y: auto;
      }
    }
  }
  .section-loader {
    min-height: 90vh;
    margin-left: 0;
    position: fixed;
  }

  .form-group {
    position: relative;

    span {
      position: absolute;
      bottom: 7px;
      right: 20px;
      color: #d33;
      opacity: 0;
      transition: ease-in 300ms opacity;
      pointer-events: none;
    }

    &.has-error {
      .form-control {
        border-color: #f90a48;
        box-shadow: 0 0 10px rgba(249, 10, 72, 0.2);
      }

      > span {
        opacity: 1;
      }
    }
  }
</style>
