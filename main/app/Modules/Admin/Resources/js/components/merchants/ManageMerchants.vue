<template>
  <main>
    <pre-loader v-if="sectionLoading" class="section-loader"></pre-loader>
    <page-header pageTitle="Manage Merchants"></page-header>
    <div class="content">
      <!-- table basic -->
      <div class="card">
        <div class="card-title">
          <button
            type="button"
            class="btn btn-bold btn-pure btn-twitter btn-shadow"
            data-toggle="modal"
            data-target="#modal-merchant"
          >Create Merchant</button>
        </div>
        <div class="card-body">
          <table class="table table-bordered table-hover" id="merchants-table">
            <thead>
              <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Merchant Code</th>
                <th>Status</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="merchant in merchants" :key="merchant.id">
                <td>{{ merchant.id }}</td>
                <td>{{ merchant.name }}</td>
                <td>{{ merchant.email }}</td>
                <td>{{ merchant.phone }}</td>
                <td>{{ merchant.unique_code }}</td>
                <td>{{ merchant.is_active ? 'Active' : 'Suspended' }}</td>
                <td>
                  <div
                    class="badge badge-success badge-shadow pointer"
                    data-toggle="modal"
                    data-target="#modal-details"
                    @click="showModal(merchant)"
                  >Transactions</div>
                </td>
              </tr>
            </tbody>
          </table>

          <div class="modal modal-left fade" id="modal-details" tabindex="-1">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h4 class="modal-title">{{ merchantDetails.full_name }}' details</h4>
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
                                <div class="fs-16 fw-500 text-success">Agent</div>
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
                                <tr v-for="(value, property, idx) in merchantDetails" :key="idx">
                                  <td>{{ slugToString(property) }}</td>
                                  <td>
                                    <span v-if="property != 'merchant_passport'">{{ value }}</span>
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
                    @click="restoreMerchant"
                    v-if="merchantDetails.is_suspended"
                  >Restore Account</button>
                  <button
                    type="button"
                    class="btn btn-bold btn-pure btn-danger"
                    @click="suspendMerchant"
                    v-else
                  >Suspend Account</button>
                </div>
              </div>
            </div>
          </div>

          <div class="modal modal-right fade" id="modal-merchant" tabindex="-1">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h4 class="modal-title">Enter Merchant Details</h4>
                  <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body">
                  <pre-loader v-if="sectionLoading" class="section-loader"></pre-loader>
                  <form class="m-25">
                    <div class="form-group mb-5" :class="{'has-error': errors.has('full_name')}">
                      <label for="form-full-name">
                        <strong>Merchant Name</strong>
                      </label>
                      <input
                        type="text"
                        class="form-control form-control-pill"
                        id="form-full-name"
                        data-vv-as="merchant name"
                        v-model="details.name"
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
                        data-vv-as="merchant's email"
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
                        data-vv-as="merchant's phone"
                        v-model="details.phone"
                        v-validate="'required'"
                        name="phone"
                      />
                      <span>{{ errors.first('phone') }}</span>
                    </div>

                    <div class="form-group mb-5 mt-20">
                      <div class="input-group mb-3 form-control-pill">
                        <label class="form-control form-control-checkbox">
                          <input
                            type="checkbox"
                            id="input-auto-generate"
                            v-model="details.auto_generate"
                            class="mr-5"
                          />
                          <label for="input-auto-generate" class="mb-0">Auto generate Merchant ID</label>
                        </label>
                      </div>
                    </div>

                    <transition name="nav-transition">
                      <div
                        class="form-group mb-5"
                        :class="{'has-error': errors.has('unique_code')}"
                        v-if="!details.auto_generate"
                      >
                        <label for="form-code">
                          <strong>Merchant Code</strong>
                        </label>
                        <input
                          type="text"
                          class="form-control form-control-pill"
                          placeholder="Alpha numeric and dash characters only"
                          id="form-code"
                          v-model="details.unique_code"
                          v-validate="'alpha_dash'"
                          name="unique_code"
                        />
                        <div class="text-danger">{{ errors.first('unique_code') }}</div>
                      </div>
                    </transition>

                    <div class="form-group mt-20">
                      <button
                        type="button"
                        class="btn btn-rss btn-round btn-block"
                        @click="createMerchant"
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
    merchantViewMerchants,
    merchantCreateMerchant,
    merchantDeleteMerchant,
    merchantRestoreMerchant,
    merchantSuspendMerchant
  } from "@admin-assets/js/config";
  import PreLoader from "@admin-components/misc/PageLoader";
  export default {
    name: "ManageMerchants",
    data: () => ({
      merchants: [],
      merchantDetails: {},
      sectionLoading: false,
      all_routes: [],
      permitted_routes: [],
      details: {}
    }),
    components: {
      PreLoader
    },
    created() {
      this.getAllMerchants();
    },
    mounted() {
      this.$emit("page-loaded");
    },
    methods: {
      showModal(merchantDetails) {
        this.merchantDetails = merchantDetails;
      },
      slugToString(slug) {
        let words = slug.split("_");

        for (let i = 0; i < words.length; i++) {
          let word = words[i];
          words[i] = word.charAt(0).toUpperCase() + word.slice(1);
        }
        return words.join(" ");
      },
      getAllMerchants() {
        BlockToast.fire({
          text: "Retrieving merchants ..."
        });
        this.sectionLoading = true;
        axios.get(merchantViewMerchants).then(({ data: { merchants } }) => {
          this.merchants = merchants;

          if (this.$isDesktop) {
            this.$nextTick(() => {
              $(function() {
                $("#merchants-table").DataTable({
                  responsive: true,
                  scrollX: false,
                  language: {
                    searchPlaceholder: "Search...",
                    sSearch: ""
                  }
                });
              });
            });
          } else {
            this.$nextTick(() => {
              $(function() {
                $("#merchants-table").DataTable({
                  responsive: false,
                  scrollX: true,
                  language: {
                    searchPlaceholder: "Search...",
                    sSearch: ""
                  }
                });
              });
            });
          }
          this.sectionLoading = false;
          swal.close();
        });
      },

      deleteMerchant() {
        this.sectionLoading = true;
        BlockToast.fire({
          text: "Deleting merchant account ..."
        });
        axios
          .delete(merchantDeleteMerchant(this.merchantDetails.id))
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
      suspendMerchant() {
        this.sectionLoading = true;
        BlockToast.fire({
          text: "suspending merchant account ..."
        });
        axios
          .put(merchantSuspendMerchant(this.merchantDetails.id))
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
      restoreMerchant() {
        this.sectionLoading = true;
        BlockToast.fire({
          text: "restore merchant account ..."
        });
        axios
          .put(merchantRestoreMerchant(this.merchantDetails.id))
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
      createMerchant() {
        this.$validator.validateAll().then(result => {
          if (!result) {
            Toast.fire({
              title: "Invalid data! Try again",
              position: "center",
              icon: "error"
            });
          } else {
            BlockToast.fire({
              text: "Creating merchant..."
            });

            axios
              .post(merchantCreateMerchant, { ...this.details })
              .then(({ status, data: { merchant } }) => {
                if (undefined !== status && status == 201) {
                  this.details = {};
                  this.merchants.push(merchant);
                  Toast.fire({
                    title: "Created",
                    text: `Merchant created`,
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
</style>
