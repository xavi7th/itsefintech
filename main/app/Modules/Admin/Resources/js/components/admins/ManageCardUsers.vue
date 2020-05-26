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
                    v-if="$user.isAccountOfficer || $user.isAdmin"
                  >View Cards</div>
                  <div
                    class="badge btn-warning pointer btn-bold"
                    data-toggle="modal"
                    data-target="#modal-merchant-trans"
                    @click="showMerchantTransModal(user)"
                    v-if="$user.isAdmin"
                  >Merchant Transactions</div>
                  <div
                    class="badge btn-bold btn-warning pointer"
                    @click="restoreCardUser(user)"
                    v-if="user.is_suspended && $user.isAccountOfficer"
                  >Restore User</div>
                  <div
                    class="badge btn-bold btn-danger pointer"
                    @click="suspendCardUser(user)"
                    v-else-if="!user.is_suspended && $user.isAccountOfficer"
                  >Suspend User</div>
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
                              <div class="flex-sh-0 ln-18">
                                <div
                                  class="fs-16 fw-500 text-danger"
                                >{{ userDetails.merchant_limit | Naira }}</div>
                                <span class="fs-12 text-light">
                                  <!-- <i class="far fa-clock"></i> -->
                                  Merchant Limit
                                </span>
                              </div>
                              <div class="flex-sh-0 ln-18">
                                <div
                                  class="fs-16 fw-500 text-warning"
                                >{{ userDetails.credit_limit | Naira }}</div>
                                <span class="fs-12 text-light">
                                  <!-- <i class="far fa-clock"></i> -->
                                  Credit Limit
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
                                <tr v-for="(value, property, idx) in userDetails" :key="idx">
                                  <td>{{ slugToString(property) }}</td>
                                  <td>
                                    <span v-if="property != 'cards'">{{ value }}</span>
                                    <a :href="value" v-else target="_blank">
                                      <img :src="value" alt class="img-fluid" />
                                    </a>
                                    <div
                                      class="badge badge-danger pointer btn-bold pull-right"
                                      @click="showFullBvnNumber(value)"
                                      v-if="$user.isAdmin && property == 'bvn'"
                                    >
                                      REVEAL
                                      <i class="fa fa-eye"></i>
                                    </div>
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
                    class="btn btn-bold btn-info btn-danger"
                    @click="adminSetCardUserCreditLimit(userDetails)"
                    data-dismiss="modal"
                    v-if="!userDetails.is_suspended && $user.isAccountOfficer"
                  >Set User's Credit Limit</button>

                  <button
                    type="button"
                    class="btn btn-bold btn-purple btn-danger"
                    @click="adminSetCardUserMerchantLimit(userDetails)"
                    data-dismiss="modal"
                    v-if="!userDetails.is_suspended && $user.isAccountOfficer"
                  >Set Merchant Limit</button>
                </div>
              </div>
            </div>
          </div>

          <div class="modal modal-right fade" id="modal-cards" tabindex="-1">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h4 class="modal-title">{{ userDetails.full_name }}'s Debit Cards</h4>
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
                                v-if="$user.isAdmin"
                                @click="showFullPANNumber(value)"
                              >
                                REVEAL
                                <i class="fa fa-eye"></i>
                              </button>
                            </div>
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

          <div class="modal modal-right fade" id="modal-merchant-trans" tabindex="-1">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h4 class="modal-title">{{ userDetails.full_name }}'s Merchant Transactions</h4>
                  <button type="button" class="close" data-dismiss="modal" ref="closeModal">
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
                            v-for="value in merchantTransactions"
                            :key="value.id"
                          >
                            <div class="flex j-c-between w-100p flex-wrap">
                              <h5 class="widget-title mt-2">{{ value.merchant.name }}</h5>
                              <span class="widget-title mt-2">{{ value.amount | currency('₦')}}</span>
                              <span class="widget-text-small">{{ value.trans_type }}</span>
                              <span class="widget-title mt-2">{{ value.description }}</span>
                              <span class="widget-title mt-2">{{ value.created_at }}</span>
                              <span
                                class="widget-title mt-2"
                              >{{ value.is_merchant_paid ? 'Merchant Paid' : 'Merchant Not Paid' }}</span>
                            </div>
                          </div>
                          <div v-if="!merchantTransactions.length">
                            <div class="flex j-c-center w-100p flex-wrap">
                              <h4 class="widget-title mt-2">NO MERCHANT TRANSACTIONS</h4>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="modal-footer j-c-between">
                  <button
                    type="button"
                    class="btn btn-bold btn-purple btn-styled"
                    @click="debitVoucher"
                    v-if="$user.isAdmin || $user.isAccountOfficer"
                  >Debit Voucher</button>
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
    adminSuspendCardUser,
    adminSetCardUserCreditLimit,
    adminSetCardUserMerchantLimit,
    adminShowFullBvnNumber,
    adminShowFullPANNumber
  } from "@admin-assets/js/config";
  import PreLoader from "@admin-components/misc/PageLoader";
  export default {
    name: "ManageCardUsers",
    data: () => ({
      users: [],
      userDetails: {},
      sectionLoading: false,
      details: {},
      merchantTransactions: []
    }),
    components: {
      PreLoader
    },
    created() {
      axios.get(adminViewCardUsers).then(({ data: { users } }) => {
        this.users = users;

        if (this.$isDesktop) {
          this.$nextTick(() => {
            $(function() {
              $("#datatable1").DataTable({
                responsive: true,
                scrollX: false,
                order: [[0, "desc"]]
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
              $("#datatable1").DataTable({
                responsive: false,
                scrollX: true,
                order: [[0, "desc"]]
                language: {
                  searchPlaceholder: "Search...",
                  sSearch: ""
                }
              });
            });
          });
        }
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
        this.userDetails = userDetails;
        this.details.email = userDetails.email;
        this.details.user_id = userDetails.id;
      },
      showMerchantTransModal(userDetails) {
        this.sectionLoading = true;
        axios
          .get(`/${userDetails.id}/merchant-transactions`)
          .then(({ data: trans }) => {
            this.merchantTransactions = trans;
            this.userDetails = userDetails;
            this.sectionLoading = false;
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
                position: "center",
                icon: "error"
              });
            }

            this.$nextTick(() => {
              $(() => {
                this.sectionLoading = false;
              });
            });
          });
      },
      suspendCardUser(user) {
        this.sectionLoading = true;
        BlockToast.fire({
          text: "suspending account officer's account ..."
        });
        axios.put(adminSuspendCardUser(user.id)).then(({ status }) => {
          if (status === 204) {
            user.is_suspended = true;
            Toast.fire({
              title: "Success",
              text: "User account suspended",
              position: "center"
            });
          } else {
            Toast.fire({
              title: "Failed",
              text: "Something wrong happend",
              position: "center",
              icon: "error"
            });
          }

          this.$nextTick(() => {
            $(() => {
              this.sectionLoading = false;
            });
          });
        });
      },
      restoreCardUser(user) {
        this.sectionLoading = true;
        BlockToast.fire({
          text: "restoring account officer's account ..."
        });
        axios.put(adminRestoreCardUser(user.id)).then(({ status }) => {
          user.is_suspended = false;
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
              position: "center",
              icon: "error"
            });
          }

          this.$nextTick(() => {
            $(() => {
              this.sectionLoading = false;
            });
          });
        });
      },
      adminSetCardUserMerchantLimit(card_user) {
        this.sectionLoading = true;

        swal
          .fire({
            title: "Enter an amount",
            html: `<div class="d-flex">
                      <input id="merchant-amount-input" class="swal2-input" required placeholder="Enter merchant limit">
                      <input id="merchant-interest-input" class="swal2-input" required placeholder="Enter interest">
                    </div>`,
            showCancelButton: true,
            confirmButtonText: "Set Merchant Limit",
            allowEscapeKey: false,
            focusCancel: true,
            cancelButtonColor: "#333",
            confirmButtonColor: "#d33",
            showLoaderOnConfirm: true,
            allowOutsideClick: () => !swal.isLoading(),
            preConfirm: () => {
              return axios
                .put(adminSetCardUserMerchantLimit(card_user.id), {
                  amount: document.getElementById("merchant-amount-input").value,
                  interest: document.getElementById("merchant-interest-input")
                    .value
                })
                .then(response => {
                  if (response.status !== 204) {
                    throw new Error(response.statusText);
                  }
                  card_user.merchant_limit = document.getElementById(
                    "merchant-amount-input"
                  ).value;
                  return { rsp: true };
                })
                .catch(error => {
                  swal.showValidationMessage(`Request failed: ${error}`);
                });
            }
          })
          .then(result => {
            if (result.value) {
              swal.fire({
                title: "Success",
                text: "Merchant Limit set",
                position: "center"
              });
            } else {
              Toast.fire({
                title: "Failed",
                text: "Something wrong happend",
                position: "center",
                icon: "error"
              });
            }
          })
          .then(() => {
            this.sectionLoading = false;
          });
      },
      adminSetCardUserCreditLimit(card_user) {
        this.sectionLoading = true;
        BlockToast.fire({
          text: "Updating user's credit limit..."
        });

        swal
          .fire({
            title: "Enter an amount",
            html: `<div class="d-flex">
                      <input id="amount-input" class="swal2-input" required placeholder="Enter credit limit">
                      <input id="interest-input" class="swal2-input" required placeholder="Enter interest">
                    </div>`,
            showCancelButton: true,
            confirmButtonText: "Set Credit Limit",
            allowEscapeKey: false,
            focusCancel: true,
            cancelButtonColor: "#333",
            confirmButtonColor: "#d33",
            showLoaderOnConfirm: true,
            allowOutsideClick: () => !swal.isLoading(),
            preConfirm: () => {
              return axios
                .put(adminSetCardUserCreditLimit(this.userDetails.id), {
                  amount: document.getElementById("amount-input").value,
                  interest: document.getElementById("interest-input").value
                })
                .then(response => {
                  if (response.status !== 204) {
                    throw new Error(response.statusText);
                  }
                  card_user.credit_limit = document.getElementById(
                    "amount-input"
                  ).value;
                  return { rsp: true };
                })
                .catch(error => {
                  swal.showValidationMessage(`Request failed: ${error}`);
                });
            }
          })
          .then(result => {
            if (result.value) {
              swal.fire({
                title: "Success",
                text: "Credit Limit set",
                position: "center"
              });
            } else {
              Toast.fire({
                title: "Failed",
                text: "Something wrong happend",
                position: "center",
                icon: "error"
              });
            }
          })
          .then(() => {
            this.sectionLoading = false;
          });
      },
      showFullPANNumber(card) {
        this.sectionLoading = true;
        BlockToast.fire({
          text: "decrypting card PAN number ..."
        });

        axios
          .get(adminShowFullPANNumber(card.id))
          .then(({ status, data: { full_pan } }) => {
            if (status === 200) {
              swal.fire(full_pan, "Card's PAN", "info");
            } else {
              Toast.fire({
                title: "Failed",
                text: "Something wrong happend",
                position: "center",
                icon: "error"
              });
            }

            this.$nextTick(() => {
              $(() => {
                this.sectionLoading = false;
              });
            });
          });
      },
      showFullBvnNumber(card_user) {
        this.sectionLoading = true;
        BlockToast.fire({
          text: "decrypting user bvn ..."
        });
        axios
          .get(adminShowFullBvnNumber(this.userDetails.id))
          .then(({ status, data: { full_bvn } }) => {
            if (status === 200) {
              swal.fire(full_bvn, "User's BVN", "info");
            } else {
              Toast.fire({
                title: "Failed",
                text: "Something wrong happend",
                position: "center",
                icon: "error"
              });
            }

            this.$nextTick(() => {
              $(() => {
                this.sectionLoading = false;
              });
            });
          });
      },
      hasExpired(date) {
        return new Date(date) < Date.now();
      },
      range(start, end) {
        return _.range(start, end);
      },
      debitVoucher() {
        // this.sectionLoading = true;
        console.log(this.$refs.closeModal.click());

        BlockToast.fire({
          text: "Debiting user´s voucher..."
        });

        swal
          .fire({
            title: "Enter an amount",
            html: `<div class="d-flex flex-wrap j-c-center">
                      <h1 class="text-danger text-center">
                        <i class="fa fa-bullseye"></i>
                        Notice!
                      </h1>
                      <p class="text-center text-danger">
                        This action will perform a direct debit on the user´s voucher credit
                      </p>
                      <input id="merchant-code" class="swal2-input" required placeholder="Merchant Code" autofocus>
                      <input id="debit-amount" class="swal2-input" required placeholder="Debit Amount">
                    </div>`,
            showCancelButton: true,
            confirmButtonText: "Debit User Voucher",
            allowEscapeKey: false,
            focusCancel: true,
            cancelButtonColor: "#333",
            confirmButtonColor: "#d33",
            showLoaderOnConfirm: true,
            allowOutsideClick: () => !swal.isLoading(),
            preConfirm: () => {
              return axios
                .post(`/merchant-transaction/${this.userDetails.id}/create`, {
                  amount: document.getElementById("debit-amount").value,
                  merchant_code: document.getElementById("merchant-code").value
                })
                .then(response => {
                  if (response && response.status !== 201) {
                    throw new Error(response.statusText);
                  }
                  return { rsp: true };
                })
                .catch(error => {
                  swal.showValidationMessage(`Request failed: ${error}`);
                });
            }
          })
          .then(result => {
            if (result.value) {
              swal.fire({
                title: "Success",
                text: "Voucher debited",
                position: "center",
                icon: "success"
              });
            } else {
              Toast.fire({
                title: "Canceled",
                text: "You cancelled the transaction",
                position: "center",
                icon: "info"
              });
            }
          })
          .then(() => {
            this.sectionLoading = false;
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

  .debit-cards {
    max-height: 60vh;
    overflow: auto;
  }
  .fz-10 {
    font-size: 10px !important;
  }

  .pull-right {
    float: right !important;
  }
</style>
