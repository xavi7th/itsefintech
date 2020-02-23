<template>
  <main>
    <page-header pageTitle="Manage Debit Card Stock Requests"></page-header>
    <div class="content">
      <!-- table basic -->
      <div class="card">
        <div class="card-title"></div>
        <div class="card-body">
          <table class="table table-bordered table-hover" id="datatable1">
            <thead>
              <tr>
                <th>ID</th>
                <th>Sales Rep</th>
                <th>Number</th>
                <th>Status</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr
                v-for="debit_card_stock_request in debit_card_stock_requests"
                :key="debit_card_stock_request.id"
              >
                <td>{{ debit_card_stock_request.id }}</td>
                <td>{{ debit_card_stock_request.sales_rep.full_name }}</td>
                <td>{{ debit_card_stock_request.number_of_cards }}</td>
                <td>{{ debit_card_stock_request.is_processed ? 'Processed' : 'Not Processed' }}</td>
                <td>
                  <div
                    class="badge badge-success badge-shadow pointer"
                    @click="markAsProcessed(debit_card_stock_request)"
                    v-if="!debit_card_stock_request.is_processed"
                  >Mark as Processed</div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </main>
</template>

<script>
  import {
    adminViewDebitCardStockRequests,
    adminMarkDebitCardStockRequestAsProcessed
  } from "@admin-assets/js/config";
  import PreLoader from "@admin-components/misc/PageLoader";
  export default {
    name: "ManageDebitCardStockRequests",
    data: () => ({
      debit_card_stock_requests: [],
      debitCardStockRequestDetails: {},
      sectionLoading: false,
      requestStatuses: {},
      details: {}
    }),
    components: {
      PreLoader
    },
    created() {
      this.getDebitCardStockRequests();
    },
    mounted() {
      this.$emit("page-loaded");
    },
    methods: {
      markAsProcessed(debitCardStockRequest) {
        this.sectionLoading = true;
        BlockToast.fire({
          text: "processing ..."
        });
        axios
          .put(
            adminMarkDebitCardStockRequestAsProcessed(debitCardStockRequest.id)
          )
          .then(({ status }) => {
            debitCardStockRequest.is_processed = true;
            if (status === 204) {
              Toast.fire({
                title: "Success",
                text: "Request has been flagged as paid",
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
      getDebitCardStockRequests() {
        axios
          .get(adminViewDebitCardStockRequests)
          .then(({ data: { stock_requests } }) => {
            this.debit_card_stock_requests = stock_requests;

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
