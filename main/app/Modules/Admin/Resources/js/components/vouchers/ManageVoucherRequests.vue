<template>
  <main>
    <pre-loader v-if="sectionLoading" class="section-loader page-loader"></pre-loader>
    <page-header pageTitle="Manage Voucher Requests"></page-header>
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
                <th>Approval Status</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="voucher_request in voucher_requests" :key="voucher_request.id">
                <td>{{ voucher_request.id }}</td>
                <td>{{ voucher_request.requester.full_name }}</td>
                <td>{{ voucher_request.requester.phone }}</td>
                <template
                  v-if="voucher_request.is_approved && voucher_request.repayment_balance == 0"
                >
                  <td colspan="2" class="text-center text-bold text-uppercase">Voucher paid</td>
                </template>
                <template v-else>
                  <td>{{ voucher_request.amount | Naira }}</td>
                  <td>{{ voucher_request.is_approved ? 'Approved' : 'Not Approved' }}</td>
                </template>
                <td>
                  <div
                    class="badge badge-info badge-shadow pointer"
                    data-toggle="modal"
                    data-target="#loan-request-details"
                    @click="showDetailsModal(voucher_request)"
                  >View Request Details</div>
                  <div
                    class="badge badge-success badge-shadow pointer"
                    @click="approveVoucherRequest(voucher_request)"
                    v-if="!voucher_request.is_approved && $user.isAccountOfficer"
                  >Approve</div>
                  <div
                    class="badge badge-purple badge-shadow pointer"
                    @click="allocateVoucher(voucher_request)"
                    v-if="!voucher_request.voucher_id  && $user.isAccountOfficer"
                  >Allocate Voucher</div>
                </td>
              </tr>
            </tbody>
          </table>

          <div class="modal modal-left fade" id="loan-request-details" tabindex="-1">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h4 class="modal-title">{{ VoucherRequestDetails.requester.full_name }}'s details</h4>
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
                                  v-for="(value, property, idx) in VoucherRequestDetails"
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
                                  v-for="(value, property) in VoucherRequestDetails.requester"
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
                                  v-for="card_details in VoucherRequestDetails.requester.cards"
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
  import { adminViewVoucherRequests } from "@admin-assets/js/config";
  import {
    accountOfficeAllocateVoucherToRequest,
    accountOfficeApproveVoucherRequest
  } from "@accountOfficer-assets/js/config";
  import PreLoader from "@admin-components/misc/PageLoader";
  export default {
    name: "ManageVoucherRequests",
    data: () => ({
      voucher_requests: [],
      VoucherRequestDetails: {
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
      this.getVoucherRequests();
    },
    mounted() {
      this.$emit("page-loaded");
    },
    methods: {
      showDetailsModal(VoucherRequestDetails) {
        this.VoucherRequestDetails = VoucherRequestDetails;
      },
      slugToString(slug) {
        let words = slug.split("_");

        for (let i = 0; i < words.length; i++) {
          let word = words[i];
          words[i] = word.charAt(0).toUpperCase() + word.slice(1);
        }
        return words.join(" ");
      },
      approveVoucherRequest(voucherRequest) {
        // this.sectionLoading = true;x
        BlockToast.fire({
          text: "processing ..."
        });

        axios
          .put(accountOfficeApproveVoucherRequest(voucherRequest.id))
          .then(({ status }) => {
            voucherRequest.is_approved = true;
            if (status === 204) {
              Toast.fire({
                title: "Success",
                text: "Request has been marked as approved",
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

      getVoucherRequests() {
        axios
          .get(adminViewVoucherRequests)
          .then(({ data: { voucher_requests } }) => {
            this.voucher_requests = voucher_requests;

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

      allocateVoucher(voucherRequestDetails) {
        swal
          .fire({
            title: "Enter voucher code",
            input: "text",
            inputAttributes: {
              autocapitalize: "off"
            },
            showCancelButton: true,
            confirmButtonText: "Allocate voucher",
            showLoaderOnConfirm: true,
            preConfirm: voucher_code => {
              return axios
                .put(
                  accountOfficeAllocateVoucherToRequest(voucherRequestDetails.id),
                  {
                    voucher_code
                  }
                )
                .then(response => {
                  if (response.status !== 204) {
                    throw new Error(response.statusText);
                  }
                  return { rsp: true };
                })
                .catch(error => {
                  if (error.response.status === 423) {
                    swal.showValidationMessage("Unassigned voucher");
                  } else {
                    swal.showValidationMessage(`Request failed: ${error}`);
                  }
                });
            },
            allowOutsideClick: () => !swal.isLoading()
          })
          .then(result => {
            if (result.value) {
              voucherRequestDetails.voucher_id = true;
              swal.fire({
                title: `Allocated`,
                text: "User can now use voucher in allied stores",
                icon: "success"
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
