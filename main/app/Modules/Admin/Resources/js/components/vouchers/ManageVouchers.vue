<template>
  <main>
    <pre-loader v-if="sectionLoading" class="section-loader"></pre-loader>
    <page-header pageTitle="Manage Vouchers"></page-header>
    <div class="content">
      <!-- table basic -->
      <div class="card">
        <div class="card-title">
          <button
            type="button"
            class="btn btn-bold btn-pure btn-twitter btn-shadow"
            data-toggle="modal"
            data-target="#modal-voucher"
          >Create Voucher</button>
        </div>
        <div class="card-body">
          <table class="table table-bordered table-hover" id="vouchers-table">
            <thead>
              <tr>
                <th>ID</th>
                <th>Card User</th>
                <th>Amount</th>
                <th>Voucher Code</th>
                <th>Status</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="voucher in vouchers" :key="voucher.id">
                <td>{{ voucher.id }}</td>
                <td>{{ voucher.card_user ? voucher.card_user.email : 'Not Assigned' }}</td>
                <td>{{ voucher.amount }}</td>
                <td>{{ voucher.code }}</td>
                <td>{{ voucher.is_expired ? 'Expired' : 'Valid' }}</td>
                <td>
                  <div
                    class="badge badge-success badge-shadow pointer"
                    data-toggle="modal"
                    data-target="#modal-transactions"
                    @click="showModal(voucher)"
                  >Transactions</div>
                </td>
              </tr>
            </tbody>
          </table>

          <div class="modal modal-left fade" id="modal-transactions" tabindex="-1">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h4 class="modal-title">{{ voucherDetails.code }}' transactions</h4>
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
                              <!-- <div class="flex-sh-0 ln-18">
                                <div class="fs-16 fw-500 text-success">Agent</div>
                                <span class="fs-12 text-light">User Role</span>
                              </div>-->
                              <div class="flex-sh-0 ln-18">
                                <div class="fs-16 fw-500 text-danger">{{ 2000 | Naira }}</div>
                                <span class="fs-12 text-light">
                                  <i class="far fa-clock"></i> Voucher amount
                                </span>
                              </div>
                              <div class="flex-sh-0 ln-18">
                                <div class="fs-16 fw-500 text-warning">{{ 1500 | Naira }}</div>
                                <span class="fs-12 text-light">
                                  <i class="far fa-clock"></i> Voucher balance
                                </span>
                              </div>
                            </div>
                            <table class="table table-striped">
                              <thead>
                                <tr>
                                  <th>Field</th>
                                  <th>Value</th>
                                </tr>
                              </thead>
                              <tbody>
                                <tr
                                  v-for="(value, property, idx) in voucherDetails.transactions"
                                  :key="idx"
                                >
                                  <td>{{ slugToString(property) }}</td>
                                  <td>
                                    <span v-if="property != 'voucher_passport'">{{ value }}</span>
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
                </div>
              </div>
            </div>
          </div>

          <div class="modal modal-right fade" id="modal-voucher" tabindex="-1">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h4 class="modal-title">Enter Voucher Details</h4>
                  <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body">
                  <pre-loader v-if="sectionLoading" class="section-loader"></pre-loader>
                  <form class="m-25">
                    <div class="form-group mb-5" :class="{'has-error': errors.has('amount')}">
                      <label for="form-amount">
                        <strong>Voucher Amount</strong>
                      </label>
                      <input
                        type="text"
                        class="form-control form-control-pill"
                        id="form-amount"
                        data-vv-as="voucher amount"
                        v-model="details.amount"
                        v-validate="'required|numeric'"
                        name="amount"
                      />
                      <span>{{ errors.first('amount') }}</span>
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
                          <label for="input-auto-generate" class="mb-0">Auto generate Voucher Code</label>
                        </label>
                      </div>
                    </div>

                    <transition name="nav-transition">
                      <div
                        class="form-group mb-5"
                        :class="{'has-error': errors.has('code')}"
                        v-if="!details.auto_generate"
                      >
                        <label for="form-code">
                          <strong>Voucher Code</strong>
                        </label>
                        <input
                          type="text"
                          class="form-control form-control-pill"
                          placeholder="Alpha numeric and dash characters only"
                          id="form-code"
                          v-model="details.code"
                          v-validate="'alpha_dash'"
                          name="code"
                        />
                        <div class="text-danger">{{ errors.first('code') }}</div>
                      </div>
                    </transition>

                    <div class="form-group mt-20">
                      <button
                        type="button"
                        class="btn btn-rss btn-round btn-block"
                        @click="createVoucher"
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
  import { adminViewVouchers, adminCreateVoucher } from "@admin-assets/js/config";
  import PreLoader from "@admin-components/misc/PageLoader";
  export default {
    name: "ManageVouchers",
    data: () => ({
      vouchers: [],
      voucherDetails: {},
      sectionLoading: false,
      details: {}
    }),
    components: {
      PreLoader
    },
    created() {
      this.getAllVouchers();
    },
    mounted() {
      this.$emit("page-loaded");
    },
    methods: {
      showModal(voucherDetails) {
        this.voucherDetails = voucherDetails;
      },
      slugToString(slug) {
        let words = slug.split("_");

        for (let i = 0; i < words.length; i++) {
          let word = words[i];
          words[i] = word.charAt(0).toUpperCase() + word.slice(1);
        }
        return words.join(" ");
      },
      getAllVouchers() {
        BlockToast.fire({
          text: "Retrieving vouchers ..."
        });
        this.sectionLoading = true;
        axios.get(adminViewVouchers).then(({ data: { vouchers } }) => {
          this.vouchers = vouchers;

          if (this.$isDesktop) {
            this.$nextTick(() => {
              $(function() {
                $("#vouchers-table").DataTable({
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
                $("#vouchers-table").DataTable({
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

      createVoucher() {
        this.$validator.validateAll().then(result => {
          if (!result) {
            Toast.fire({
              title: "Invalid data! Try again",
              position: "center",
              icon: "error"
            });
          } else {
            BlockToast.fire({
              text: "Creating voucher..."
            });

            axios
              .post(adminCreateVoucher, { ...this.details })
              .then(({ status, data: { voucher } }) => {
                if (undefined !== status && status == 201) {
                  this.details = {};
                  this.vouchers.push(voucher);
                  Toast.fire({
                    title: "Created",
                    text: `Voucher created`,
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
