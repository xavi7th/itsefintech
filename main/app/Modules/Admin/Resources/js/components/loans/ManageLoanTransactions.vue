<template>
  <main>
    <pre-loader v-if="sectionLoading" class="section-loader page-loader"></pre-loader>
    <page-header pageTitle="Manage Loan Transactions"></page-header>
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
                <th>Phone</th>
                <th>Amount</th>
                <th>Next Due Date</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="loan_transaction in loan_transactions" :key="loan_transaction.id">
                <td>{{ loan_transaction.id }}</td>
                <td>{{ loan_transaction.requester.full_name }}</td>
                <td>{{ loan_transaction.requester.phone }}</td>

                <td>{{ loan_transaction.amount | Naira }}</td>
                <td>{{ loan_transaction.next_installment_due_date }}</td>

                <td>
                  <div
                    class="badge badge-info badge-shadow pointer"
                    data-toggle="modal"
                    data-target="#loan-transaction-details"
                    @click="showDetailsModal(loan_transaction)"
                  >View Transaction Details</div>
                </td>
              </tr>
            </tbody>
          </table>

          <div class="modal modal-left fade" id="loan-transaction-details" tabindex="-1">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h4 class="modal-title">{{ LoanTransactionDetails.full_name }}' details</h4>
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
                                <tr
                                  v-for="(value, property, idx) in LoanTransactionDetails"
                                  :key="idx"
                                >
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
                                  v-for="(value, property) in LoanTransactionDetails.requester"
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
                                  v-for="card_details in LoanTransactionDetails.requester.cards"
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
  import { adminViewLoanTransactions } from "@admin-assets/js/config";
  import PreLoader from "@admin-components/misc/PageLoader";
  export default {
    name: "ManageLoanTransactions",
    data: () => ({
      loan_transactions: [],
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
      this.getLoanTransactions();
    },
    mounted() {
      this.$emit("page-loaded");
    },
    methods: {
      showDetailsModal(LoanTransactionDetails) {
        this.LoanTransactionDetails = LoanTransactionDetails;
      },
      slugToString(slug) {
        let words = slug.split("_");

        for (let i = 0; i < words.length; i++) {
          let word = words[i];
          words[i] = word.charAt(0).toUpperCase() + word.slice(1);
        }
        return words.join(" ");
      },

      getLoanTransactions() {
        axios
          .get(adminViewLoanTransactions)
          .then(({ data: { loan_transactions } }) => {
            this.loan_transactions = loan_transactions;

            if (this.$isDesktop) {
              this.$nextTick(() => {
                $(function() {
                  $("#datatable1").DataTable({
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
                  $("#datatable1").DataTable({
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
