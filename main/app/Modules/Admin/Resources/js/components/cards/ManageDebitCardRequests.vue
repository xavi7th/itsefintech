<template>
  <main>
    <page-header pageTitle="Manage Debit Card Requests"></page-header>
    <div class="content">
      <!-- table basic -->
      <div class="card">
        <div class="card-title"></div>
        <div class="card-body">
          <table class="table table-bordered table-hover" id="datatable1">
            <thead>
              <tr>
                <th>ID</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Type</th>
                <th>Status</th>
                <th>Payment Status</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="debit_card_request in debit_card_requests" :key="debit_card_request.id">
                <td>{{ debit_card_request.id }}</td>
                <td>{{ debit_card_request.requester.email }}</td>
                <td>{{ debit_card_request.requester.phone }}</td>
                <td>{{ debit_card_request.requested_card_type }}</td>
                <td>{{ debit_card_request.status }}</td>
                <td>{{ debit_card_request.is_payment_confirmed ? 'Payment Confirmed' : debit_card_request.is_paid ? 'Payment made' : 'Not Paid' }}</td>
                <td>
                  <div
                    class="badge badge-info badge-shadow pointer"
                    data-toggle="modal"
                    data-target="#modal-details"
                    @click="showDetailsModal(debit_card_request)"
                  >View Request Details</div>
                  <div
                    class="badge badge-success badge-shadow pointer"
                    @click="markAsPaid(debit_card_request)"
                    v-if="!debit_card_request.is_paid"
                  >Mark as Paid</div>
                  <div
                    class="badge badge-warning btn-bold pointer"
                    @click="confirmPayment(debit_card_request)"
                    v-if="debit_card_request.is_paid && !debit_card_request.is_payment_confirmed"
                  >Confirm Payment</div>
                  <div
                    class="badge badge-purple badge-shadow pointer"
                    @click="allocateCard(debit_card_request)"
                    v-if="!debit_card_request.debit_card_id"
                  >Allocate Card</div>
                </td>
              </tr>
            </tbody>
          </table>

          <div class="modal modal-left fade" id="modal-details" tabindex="-1">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h4 class="modal-title">{{ debitCardRequestDetails.full_name }}' details</h4>
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
                                  v-for="(value, property, idx) in debitCardRequestDetails"
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
                                  v-for="(value, property) in debitCardRequestDetails.requester"
                                  :key="property"
                                >
                                  <td>{{ slugToString(property) }}</td>
                                  <td>
                                    <span v-if="property != 'requester'">{{ value }}</span>
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
                  <div class="form-group mb-5" :class="{'has-error': errors.has('exp_year')}">
                    <select
                      class="form-control"
                      id="form-year"
                      name="exp_year"
                      v-validate="'required'"
                      data-vv-as="expiry year"
                      v-model="debitCardRequestDetails.debit_card_request_status_id"
                    >
                      <option v-for="n in requestStatuses" :key="n.name" :value="n.id">{{ n.name }}</option>
                    </select>
                  </div>
                  <button
                    type="button"
                    class="btn btn-bold btn-pure btn-info ml-15 my-30"
                    @click="updateDebitCardRequestStatus"
                  >Update Status</button>
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
    adminViewDebitCardRequests,
    adminUpdateDebitCardRequestStatus,
    adminMarkDebitCardRequestAsPaid,
    adminAllocateDebitCardToRequest,
    adminConfirmDebitCardRequestPayment
  } from "@admin-assets/js/config";
  import PreLoader from "@admin-components/misc/PageLoader";
  export default {
    name: "ManageDebitCardRequests",
    data: () => ({
      debit_card_requests: [],
      debitCardRequestDetails: {},
      sectionLoading: false,
      requestStatuses: {},
      details: {}
    }),
    components: {
      PreLoader
    },
    created() {
      this.getDebitCardRequests();
    },
    mounted() {
      this.$emit("page-loaded");
    },
    methods: {
      showDetailsModal(debitCardRequestDetails) {
        this.debitCardRequestDetails = debitCardRequestDetails;
      },
      slugToString(slug) {
        let words = slug.split("_");

        for (let i = 0; i < words.length; i++) {
          let word = words[i];
          words[i] = word.charAt(0).toUpperCase() + word.slice(1);
        }
        return words.join(" ");
      },
      markAsPaid(debitCardRequest) {
        this.sectionLoading = true;
        BlockToast.fire({
          text: "processing ..."
        });
        axios
          .put(adminMarkDebitCardRequestAsPaid(debitCardRequest.id))
          .then(({ status }) => {
            debitCardRequest.is_paid = true;
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
      confirmPayment(debitCardRequest) {
        this.sectionLoading = true;
        BlockToast.fire({
          text: "processing ..."
        });
        axios
          .put(adminConfirmDebitCardRequestPayment(debitCardRequest.id))
          .then(({ status }) => {
            if (status === 204) {
              debitCardRequest.is_payment_confirmed = true;
              Toast.fire({
                title: "Success",
                text: "The card payment has been confirmed",
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
      allocateCard(debitCardDetails) {
        swal
          .fire({
            title: "Enter a card number",
            input: "number",
            inputAttributes: {
              autocapitalize: "off"
            },
            showCancelButton: true,
            confirmButtonText: "Allocate card",
            showLoaderOnConfirm: true,
            preConfirm: card_number => {
              return axios
                .put(adminAllocateDebitCardToRequest(debitCardDetails.id), {
                  card_number
                })
                .then(response => {
                  if (response.status !== 204) {
                    throw new Error(response.statusText);
                  }
                  return { rsp: true };
                })
                .catch(error => {
                  if (error.response.status === 423) {
                    swal.showValidationMessage("Unassigned Card");
                  } else {
                    swal.showValidationMessage(`Request failed: ${error}`);
                  }
                });
            },
            allowOutsideClick: () => !swal.isLoading()
          })
          .then(result => {
            if (result.value) {
              swal
                .fire({
                  title: `Allocated`,
                  text:
                    "The user will be required to activate the card before using it!",
                  icon: "success"
                })
                .then(() => {
                  location.reload();
                });
            }
          });
      },
      updateDebitCardRequestStatus() {
        this.sectionLoading = true;
        BlockToast.fire({
          text: "Updating debit card request status ..."
        });
        axios
          .put(
            adminUpdateDebitCardRequestStatus(this.debitCardRequestDetails.id),
            {
              details: this.debitCardRequestDetails
            }
          )
          .then(({ status, data: { new_status } }) => {
            if (status === 203) {
              this.debitCardRequestDetails.status = new_status;
              Toast.fire({
                title: "Success",
                text: "Request status updated",
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
      getDebitCardRequests() {
        axios
          .get(adminViewDebitCardRequests)
          .then(({ data: { debit_card_requests, request_statuses } }) => {
            this.debit_card_requests = debit_card_requests;
            this.requestStatuses = request_statuses;

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
