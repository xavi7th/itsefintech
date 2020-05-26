<template>
  <main>
    <pre-loader v-if="sectionLoading" class="section-loader page-loader"></pre-loader>
    <page-header pageTitle="Manage Loan Recovery"></page-header>
    <div class="content">
      <!-- table basic -->
      <div class="card">
        <div class="card-title"></div>
        <div class="card-body">
          <table class="table table-bordered table-hover" id="datatable1">
            <thead>
              <tr>
                <th>ID</th>
                <th>User</th>
                <th>Loan Balance</th>
                <th>Amount Paid</th>
                <th>To pay</th>
                <th>Next Due Date</th>
                <th>Status</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr
                v-for="loan_request in loan_requests"
                :key="loan_request.id"
                :class="{'bg-pale-pink': loan_request.is_due}"
              >
                <td>{{ loan_request.id }}</td>
                <td>{{ loan_request.requester.full_name }}</td>
                <td>{{ loan_request.loan_balance | Naira}}</td>
                <td>{{ loan_request.amount_paid | Naira}}</td>
                <td>{{ loan_request.scheduled_repayment_amount | Naira}}</td>
                <td>{{ loan_request.next_due_date }}</td>

                <td
                  class="text-center text-bold text-uppercase"
                >{{loan_request.is_due ? 'DEFAULTER' : 'NOT DUE' }}</td>

                <td>
                  <div
                    class="btn btn-info bg-dark mb-10 pointer btn-xs"
                    data-toggle="modal"
                    data-target="#loan-request-details"
                    @click="showDetailsModal(loan_request)"
                  >Details</div>
                  <div
                    class="btn btn-purple pointer btn-xs"
                    data-toggle="modal"
                    data-target="#loan-transactions"
                    @click="showTransactionsModal(loan_request)"
                  >Transactions</div>
                </td>
              </tr>
            </tbody>
          </table>

          <div class="modal modal-right fade" id="loan-transactions" tabindex="-2">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h4 class="modal-title">Transaction's Details</h4>
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
                            <table class="table table-striped table-hover table-bordered">
                              <thead>
                                <tr>
                                  <th>Amount</th>
                                  <th>Type</th>
                                  <th>Date</th>
                                </tr>
                              </thead>
                              <tbody>
                                <tr v-for="(value, idx) in LoanTransactionDetails" :key="idx">
                                  <td>{{ value.amount | Naira}}</td>
                                  <td>
                                    <span>{{ value.transaction_type }}</span>
                                  </td>
                                  <td>
                                    <span>{{ value.date }}</span>
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
                <div class="modal-footer j-c-between">
                  <button
                    type="button"
                    class="btn btn-pink badge-shadow"
                    @click="addManualTransaction"
                    v-if="$user.isAccountOfficer || $user.isAdmin"
                  >Add Transaction</button>
                  <button
                    ref="closeDetailsModal"
                    type="button"
                    class="btn btn-bold btn-secondary"
                    data-dismiss="modal"
                  >Close</button>
                </div>
              </div>
            </div>
          </div>

          <div class="modal modal-left fade" id="loan-request-details" tabindex="-1">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h4 class="modal-title">{{ LoanRequestDetails.full_name }}´s details</h4>
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
                            <table class="table table-striped">
                              <thead>
                                <tr>
                                  <th>Field</th>
                                  <th>Value</th>
                                </tr>
                              </thead>
                              <tbody>
                                <tr v-for="(value, property, idx) in LoanRequestDetails" :key="idx">
                                  <td v-if="property != 'requester'">{{ slugToString(property) }}</td>
                                  <td>
                                    <span v-if="property != 'requester'">{{ value }}</span>
                                  </td>
                                </tr>

                                <tr>
                                  <td colspan="2">
                                    <h4 class="text-uppercase">Requester Full Details</h4>
                                  </td>
                                </tr>
                                <tr
                                  v-for="(value, property) in LoanRequestDetails.requester"
                                  :key="property"
                                >
                                  <td v-if="property != 'cards'">{{ slugToString(property) }}</td>
                                  <td>
                                    <span v-if="property != 'cards'">{{ value }}</span>
                                  </td>
                                </tr>

                                <tr>
                                  <td colspan="2">
                                    <h4 class="text-uppercase">Requester Cards Details</h4>
                                  </td>
                                </tr>
                                <template
                                  v-for="card_details in LoanRequestDetails.requester.cards"
                                >
                                  <tr
                                    v-for="(value, property) in card_details"
                                    :key="property + value"
                                  >
                                    <td>{{ slugToString(property) }}</td>
                                    <td>
                                      <span>{{ value }}</span>
                                    </td>
                                  </tr>
                                </template>
                              </tbody>
                            </table>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="modal-footer j-c-between">
                  <button
                    type="button"
                    class="btn btn-purple badge-shadow"
                    @click="sendReminderNotifications"
                    v-if="LoanRequestDetails.needs_reminder && ($user.isAccountant || $user.isAdmin)"
                  >Send Reminder</button>
                  <button
                    type="button"
                    class="btn btn-bold btn-secondary"
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
  import { adminViewLoanRequests } from "@admin-assets/js/config";
  import PreLoader from "@admin-components/misc/PageLoader";
  export default {
    name: "ManageLoanRecovery",
    data: () => ({
      loan_requests: [],
      LoanRequestDetails: {
        requester: {
          cards: {}
        }
      },
      LoanTransactionDetails: {
        requester: {
          cards: {}
        }
      },
      sectionLoading: false,
      details: {}
    }),
    components: {
      PreLoader
    },
    created() {
      this.getLoanRecovery();
    },
    mounted() {
      this.$emit("page-loaded");
    },
    methods: {
      showDetailsModal(LoanRequestDetails) {
        this.LoanRequestDetails = LoanRequestDetails;
      },
      showTransactionsModal(transactionDetails) {
        this.LoanRequestDetails = transactionDetails;
        this.LoanTransactionDetails = transactionDetails.loan_transactions;
      },
      slugToString(slug) {
        let words = slug.split("_");

        for (let i = 0; i < words.length; i++) {
          let word = words[i];
          words[i] = word.charAt(0).toUpperCase() + word.slice(1);
        }
        return words.join(" ");
      },
      addManualTransaction() {
        this.sectionLoading = true;
        this.$refs.closeDetailsModal.click();

        swal
          .fire({
            title: "Enter an amount",
            html: `<div class="d-flex flex-wrap j-c-center">
                                                        <h1 class="text-danger text-center">
                                                          <i class="fa fa-bullseye"></i>
                                                          Notice!
                                                        </h1>
                                                        <p class="text-center text-danger">
                                                          This action will affect the user's loan balance.
                                                        </p>
                                                        <input id="amount-input" class="swal2-input" placeholder="Enter Amount">
                                                        <select id="transaction-type-input" class="swal2-input">
                                                          <option>repayment</option>
                                                          <option>servicing</option>
                                                          <option>others</option>
                                                        </select>
                                                      </div>`,
            showCancelButton: true,
            confirmButtonText: "Create Transaction",
            allowEscapeKey: false,
            focusCancel: true,
            cancelButtonColor: "#333",
            confirmButtonColor: "#d33",
            showLoaderOnConfirm: true,
            allowOutsideClick: () => !swal.isLoading(),
            preConfirm: () => {
              return axios
                .put(
                  `/admin-panel/api/loan-request/${this.LoanRequestDetails.id}/transaction/add`,
                  {
                    amount: document.getElementById("amount-input").value,
                    transaction_type: document.getElementById(
                      "transaction-type-input"
                    ).value
                  }
                )
                .then(response => {
                  if (response && response.status !== 204) {
                    throw new Error(response.statusText);
                  }
                  this.sectionLoading = false;
                  this.getLoanRecovery();
                  return { rsp: true };
                })
                .catch(error => {
                  this.sectionLoading = false;
                  swal.showValidationMessage(`Request failed: ${error}`);
                });
            }
          })
          .then(result => {
            if (result.value) {
              swal.fire({
                title: "Success",
                text: "Loan transaction recorded",
                position: "center"
              });
            } else {
              Toast.fire({
                title: "Cancelled",
                text: "You canceled the transaction or something else went wrong",
                position: "center",
                icon: "info"
              });
            }
          })
          .then(() => {
            this.sectionLoading = false;
          });
      },
      sendReminderNotifications() {
        this.sectionLoading = true;
        BlockToast.fire({
          text: "processing ..."
        });

        axios
          .post(
            `/admin-panel/api/loan-request/${this.LoanRequestDetails.id}/remind`
          )
          .then(({ status }) => {
            if (status === 200) {
              this.LoanRequestDetails.needs_reminder = false;
              Toast.fire({
                title: "Success",
                text: "Reminder messages sent",
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
      getLoanRecovery() {
        axios
          .get("/admin-panel/api/loan-recovery")
          .then(({ data: { loan_requests } }) => {
            this.loan_requests = loan_requests;

            if (this.$isDesktop) {
              this.$nextTick(() => {
                $(function() {
                  if ($.fn.dataTable.isDataTable("#datatable1")) {
                    let table = $("#datatable1").DataTable();
                  } else {
                    $("#datatable1").DataTable({
                      responsive: true,
                      scrollX: false,
                      order: [[0, "desc"]],
                      language: {
                        searchPlaceholder: "Search...",
                        sSearch: ""
                      }
                    });
                  }
                });
              });
            } else {
              this.$nextTick(() => {
                $(function() {
                  if ($.fn.dataTable.isDataTable("#datatable1")) {
                    let table = $("#datatable1").DataTable();
                  } else {
                    $("#datatable1").DataTable({
                      responsive: false,
                      scrollX: true,
                      order: [[0, "desc"]],
                      language: {
                        searchPlaceholder: "Search...",
                        sSearch: ""
                      }
                    });
                  }
                });
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
    &.page-loader {
      margin-left: 260px;
    }
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
