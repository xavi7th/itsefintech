<template>
  <main>
    <page-header pageTitle="Manage Card Users"></page-header>
    <div class="content">
      <!-- table basic -->
      <div class="card">
        <div class="card-title"></div>
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
                <td>{{ user.is_suspended ? 'Suspended Account' : user.is_verified ? 'Account Verified' : 'Unverified Account' }}</td>
                <td>
                  <div
                    class="badge badge-success badge-shadow pointer"
                    data-toggle="modal"
                    data-target="#modal-details"
                    @click="showModal(user)"
                  >View Full Details</div>
                  <div
                    class="badge badge-purple pointer btn-bold"
                    data-toggle="modal"
                    data-target="#modal-cards"
                    @click="showCardsModal(user)"
                  >View Cards</div>
                </td>
              </tr>
            </tbody>
          </table>

          <div class="modal modal-left fade" id="modal-details" tabindex="-1">
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
                            <div class="flex j-c-between bd bg-light py-30 px-30">
                              <div class="flex-sh-0 ln-18">
                                <div class="fs-16 fw-500 text-success">Card User</div>
                                <span class="fs-12 text-light">User Role</span>
                              </div>
                              <!-- <div class="flex-sh-0 ln-18">
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
                              </div>-->
                            </div>
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
                  <button
                    type="button"
                    class="btn btn-bold btn-pure btn-warning"
                    @click="restoreCardUser"
                    v-if="userDetails.is_suspended"
                  >Restore Account</button>
                  <button
                    type="button"
                    class="btn btn-bold btn-pure btn-danger"
                    @click="suspendCardUser"
                    v-else
                  >Suspend Account</button>
                </div>
              </div>
            </div>
          </div>

          <div class="modal modal-right fade" id="modal-cards" tabindex="-1">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h4 class="modal-title">{{ userDetails.full_name }}' Debit Cards</h4>
                  <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body">
                  <pre-loader v-if="sectionLoading" class="section-loader"></pre-loader>
                  <div class="col-md-12">
                    <div class="card debit-cards">
                      <div class="card-body py-0">
                        <div class="card-row">
                          <div
                            class="widget-item pt-20 pb-25"
                            v-for="value in userDetails.cards"
                            :key="value.id"
                          >
                            <div class="flex j-c-between w-100p">
                              <span class="widget-title mt-2">{{ value.card_number }}</span>
                              <span
                                class="widget-text-small"
                              >{{ hasExpired(value.exp_date) ? 'expired' : 'valid' }}</span>
                              <button
                                type="button"
                                class="btn btn-bold btn-info btn-xs fz-10"
                              >Details</button>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="card">
                      <div class="card-title flex j-c-between">
                        <h3>Add New card</h3>
                      </div>

                      <div class="card-body py-0">
                        <div class="card-row">
                          <form class="m-25" @submit.prevent="createDebitCard">
                            <div
                              class="form-group mb-5"
                              :class="{'has-error': errors.has('email')}"
                            >
                              <label for="form-mail">
                                <strong>E-Mail</strong>
                              </label>
                              <input
                                type="text"
                                class="form-control form-control-pill"
                                id="form-mail"
                                v-model="details.email"
                                name="email"
                                readonly
                              />
                              <input type="hidden" v-model="details.user_id" />
                            </div>

                            <div
                              class="form-group mb-5"
                              :class="{'has-error': errors.has('card_number')}"
                            >
                              <label for="form-full-name">
                                <strong>Card Number</strong>
                              </label>
                              <input
                                type="text"
                                class="form-control form-control-pill"
                                id="form-full-name"
                                v-model="details.card_number"
                                v-validate="'required|credit_card'"
                                data-vv-as="credit card number"
                                name="card_number"
                              />
                              <span>{{ errors.first('card_number') }}</span>
                            </div>
                            <div class="row">
                              <div class="col-4">
                                <div
                                  class="form-group mb-5"
                                  :class="{'has-error': errors.has('exp_year')}"
                                >
                                  <label for="form-year">
                                    <strong>Year</strong>
                                  </label>
                                  <select
                                    class="form-control"
                                    id="form-year"
                                    name="exp_year"
                                    v-validate="'required'"
                                    data-vv-as="expiry year"
                                    v-model="details.year"
                                  >
                                    <option value>Pick a Year</option>
                                    <option v-for="n in range(2021, 2099)" :key="n">{{ n }}</option>
                                  </select>
                                </div>
                              </div>
                              <div class="col-4">
                                <div
                                  class="form-group mb-5"
                                  :class="{'has-error': errors.has('exp_month')}"
                                >
                                  <label for="form-year">
                                    <strong>Month</strong>
                                  </label>
                                  <select
                                    class="form-control"
                                    id="form-year"
                                    name="exp_month"
                                    v-validate="'required'"
                                    data-vv-as="expiry month"
                                    v-model="details.month"
                                  >
                                    <option value>Pick a Month</option>
                                    <option v-for="n in range(1, 12)" :key="n">{{ n }}</option>
                                  </select>
                                </div>
                              </div>
                              <div class="col-4">
                                <div
                                  class="form-group mb-5"
                                  :class="{'has-error': errors.has('csc')}"
                                >
                                  <label for="form-full-name">
                                    <strong>CSC/CVV</strong>
                                  </label>
                                  <input
                                    type="text"
                                    class="form-control form-control-pill"
                                    id="form-full-name"
                                    v-model="details.csc"
                                    v-validate="'required|numeric'"
                                    data-vv-as="card security code"
                                    name="csc"
                                  />
                                </div>
                              </div>

                              <span class="text-danger">{{ errors.first('csc') }}</span>
                              <span class="text-danger">{{ errors.first('exp_year') }}</span>
                              <span class="text-danger">{{ errors.first('exp_month') }}</span>
                            </div>

                            <div class="form-group mt-20">
                              <button
                                type="submit"
                                class="btn btn-rss btn-round btn-block btn-bold"
                              >Create</button>
                            </div>
                          </form>
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
        </div>
      </div>
    </div>
  </main>
</template>

<script>
  import {
    adminViewCardUsers,
    adminCardUserPermissions,
    adminCreateDebitCard,
    adminDeleteCardUser,
    adminRestoreCardUser,
    adminSuspendCardUser
  } from "@admin-assets/js/config";
  import PreLoader from "@admin-components/misc/PageLoader";
  export default {
    name: "ManageCardUsers",
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
      axios.get(adminViewCardUsers).then(({ data: { users } }) => {
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
      showCardsModal(userDetails) {
        console.log(userDetails);
        this.userDetails = userDetails;
        this.details.email = userDetails.email;
        this.details.user_id = userDetails.id;
      },
      slugToString(slug) {
        let words = slug.split("_");

        for (let i = 0; i < words.length; i++) {
          let word = words[i];
          words[i] = word.charAt(0).toUpperCase() + word.slice(1);
        }
        return words.join(" ");
      },
      deleteCardUser() {
        this.sectionLoading = true;
        BlockToast.fire({
          text: "Deleting account officer's account ..."
        });
        axios
          .delete(adminDeleteCardUser(this.userDetails.id))
          .then(({ status }) => {
            if (status === 204) {
              Toast.fire({
                title: "Success",
                text: "User account deleted",
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
      suspendCardUser() {
        this.sectionLoading = true;
        BlockToast.fire({
          text: "suspending account officer's account ..."
        });
        axios
          .put(adminSuspendCardUser(this.userDetails.id))
          .then(({ status }) => {
            if (status === 204) {
              Toast.fire({
                title: "Success",
                text: "User account suspended",
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
      restoreCardUser() {
        this.sectionLoading = true;
        BlockToast.fire({
          text: "restoring account officer's account ..."
        });
        axios
          .put(adminRestoreCardUser(this.userDetails.id))
          .then(({ status }) => {
            if (status === 204) {
              Toast.fire({
                title: "Success",
                text: "User account restored",
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
      createDebitCard() {
        this.$validator.validateAll().then(result => {
          if (!result) {
            Toast.fire({
              title: "Invalid data! Try again",
              position: "center",
              icon: "error"
            });
          } else {
            BlockToast.fire({
              text: "creating debit card for user..."
            });

            axios
              .post(adminCreateDebitCard(this.details.user_id), {
                ...this.details
              })
              .then(({ status, data: { rsp } }) => {
                console.log(rsp);

                if (undefined !== status && status == 201) {
                  this.details = {};
                  this.userDetails = {};
                  Toast.fire({
                    title: "Created",
                    text: `They will be required to set a activate it before usage`,
                    icon: "success",
                    position: "center"
                  });
                }
              })
              .catch(err => {
                if (err.response.status == 500) {
                  swal.fire({
                    title: "Error",
                    text: `Something went wrong on server. Creation not successful.`,
                    icon: "error"
                  });
                }
              });
          }
        });
      },
      hasExpired(date) {
        return new Date(date) < Date.now();
      },
      range(start, end) {
        return _.range(start, end);
      }
    }
  };
</script>

<style lang="scss" scoped>
  .modal-right,
  .modal-left {
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

  .debit-cards {
    max-height: 40vh;
    overflow: auto;
  }
  .fz-10 {
    font-size: 10px !important;
  }
</style>
