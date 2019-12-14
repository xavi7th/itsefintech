<template>
  <main>
    <page-header pageTitle="Manage Debit Cards"></page-header>
    <div class="content">
      <!-- table basic -->
      <div class="card">
        <div class="card-title">
          <button
            type="button"
            class="btn btn-bold btn-pure btn-twitter btn-shadow"
            data-toggle="modal"
            data-target="#modal-card"
            v-if="!saleRepId"
          >Create Debit Card</button>
        </div>
        <div class="card-body">
          <table class="table table-bordered table-hover" id="datatable1">
            <thead>
              <tr>
                <th>ID</th>
                <th>Card User Email</th>
                <th>Sales Rep</th>
                <th>Card Number</th>
                <th>Status</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="debitCard in debitCards" :key="debitCard.id">
                <td>{{ debitCard.id }}</td>
                <td>{{ !debitCard.card_user ? 'Unallocated' : debitCard.card_user.email }}</td>
                <td>{{ !debitCard.sales_rep ? 'Unassigned' : debitCard.sales_rep.email }}</td>
                <td>{{ debitCard.card_number }}</td>
                <td>{{ hasExpired(debitCard.exp_date) ? 'Expired' : debitCard.is_suspended ? 'Card Suspended' : debitCard.is_admin_activated ? 'Fully Activated' : debitCard.is_user_activated ? 'Pending Confirmation' : 'Not Activated' }}</td>
                <td>
                  <div
                    class="fs-11 btn btn-bold badge badge-success badge-shadow pointer"
                    @click="activateDebitCard(debitCard)"
                    v-if="!debitCard.is_admin_activated && debitCard.is_user_activated"
                  >Activate Card</div>
                  <div
                    class="fs-11 btn btn-bold badge badge-primary pointer"
                    @click="toggleDebitCardSuspension(debitCard)"
                    v-if="debitCard.is_admin_activated && debitCard.is_user_activated && !debitCard.is_suspended"
                  >Suspend Card</div>
                  <div
                    class="fs-11 btn btn-bold badge badge-purple pointer"
                    @click="toggleDebitCardSuspension(debitCard)"
                    v-if="debitCard.is_suspended"
                  >Unsuspend Card</div>
                  <div
                    class="fs-11 btn btn-bold badge badge-purple pointer"
                    @click="assignCard(debitCard)"
                    v-if="!debitCard.sales_rep && $user.type == 'admin'"
                  >Assign Card</div>

                  <div
                    class="fs-11 btn btn-bold badge badge-success pointer"
                    @click="allocateCard(debitCard)"
                    v-if="debitCard.sales_rep && !debitCard.card_user && $user.type == 'admin'"
                  >Allocate Card</div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
        <div class="modal modal-right fade" id="modal-card" tabindex="-1">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <!-- <h4 class="modal-title">Add Debit Card</h4> -->
                <button type="button" class="close" data-dismiss="modal">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <pre-loader v-if="sectionLoading" class="section-loader"></pre-loader>
                <div class="col-md-12">
                  <div class="card">
                    <div class="card-title flex j-c-between">
                      <h3>Add New card</h3>
                    </div>

                    <div class="card-body py-0">
                      <div class="card-row">
                        <form class="m-25" @submit.prevent="createDebitCard">
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
                              v-validate="'required'"
                              data-vv-as="credit card number"
                              name="card_number"
                            />
                            <!-- v-validate="'required|credit_card'" -->
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
  </main>
</template>

<script>
  import {
    adminViewDebitCards,
    adminActivateDebitCard,
    adminAssignDebitCard,
    adminAllocateDebitCard,
    toggleDebitCardSuspension,
    adminCreateDebitCard
  } from "@admin-assets/js/config";
  import PreLoader from "@admin-components/misc/PageLoader";
  export default {
    name: "ManageDebitCards",
    data: () => ({
      debitCards: [],
      debitCardDetails: {},
      sectionLoading: false,
      details: {},
      userDetails: {}
    }),
    components: {
      PreLoader
    },
    created() {
      this.getDebitCards();
    },
    mounted() {
      this.$emit("page-loaded");
    },
    methods: {
      slugToString(slug) {
        let words = slug.split("_");

        for (let i = 0; i < words.length; i++) {
          let word = words[i];
          words[i] = word.charAt(0).toUpperCase() + word.slice(1);
        }
        return words.join(" ");
      },
      getDebitCards() {
        BlockToast.fire({
          text: "loading debit cards..."
        });
        axios
          .get(adminViewDebitCards(this.saleRepId))
          .then(({ data: { debit_cards } }) => {
            this.debitCards = debit_cards;

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

            swal.close();
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
              .post(adminCreateDebitCard, {
                ...this.details
              })
              .then(({ status, data: { rsp } }) => {
                if (undefined !== status && status == 201) {
                  this.details = {};
                  this.userDetails = {};
                  Toast.fire({
                    title: "Created",
                    text: `It can now bw assigned to a sales rep's stock`,
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
      assignCard(debitCardDetails) {
        swal
          .fire({
            title: "Enter a Sales Rep's email",
            input: "text",
            inputAttributes: {
              autocapitalize: "off"
            },
            showCancelButton: true,
            confirmButtonText: "Assign card",
            showLoaderOnConfirm: true,
            preConfirm: email => {
              return axios
                .put(adminAssignDebitCard(debitCardDetails.id), {
                  email
                })
                .then(response => {
                  if (response.status !== 204) {
                    throw new Error(response.statusText);
                  }
                  return { rsp: true };
                })
                .catch(error => {
                  console.log(error.response);

                  if (error.response.status === 404) {
                    swal.showValidationMessage(`Sales Rep details not found`);
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
                  title: `Success`,
                  text: "Assigned to sales rep!",
                  icon: "success"
                })
                .then(() => {
                  location.reload();
                });
            }
          });
      },
      allocateCard(debitCardDetails) {
        swal
          .fire({
            title: "Enter a User's email",
            input: "text",
            inputAttributes: {
              autocapitalize: "off"
            },
            showCancelButton: true,
            confirmButtonText: "Allocate card",
            showLoaderOnConfirm: true,
            preConfirm: email => {
              return axios
                .put(adminAllocateDebitCard(debitCardDetails.id), {
                  email
                })
                .then(response => {
                  if (undefined === response) {
                    throw new Error("");
                  }

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
      activateDebitCard(debit_card) {
        this.sectionLoading = true;
        // BlockToast.fire({
        //   text: "updating card status ..."
        // });
        axios
          .put(adminActivateDebitCard(debit_card.id), {
            permitted_routes: this.permitted_routes
          })
          .then(({ status }) => {
            if (status === 204) {
              debit_card.is_admin_activated = true;
              Toast.fire({
                title: "Success",
                text: "Card activated",
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
      toggleDebitCardSuspension(debit_card) {
        this.sectionLoading = true;
        BlockToast.fire({
          text: "updating card status ..."
        });
        axios
          .put(toggleDebitCardSuspension(debit_card.id), {
            permitted_routes: this.permitted_routes
          })
          .then(({ status }) => {
            if (status === 204) {
              let msg = debit_card.is_suspended
                ? "Card has been unsuspended"
                : "Card has been suspended";
              debit_card.is_suspended = !debit_card.is_suspended;
              Toast.fire({
                title: "Success",
                text: msg,
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
      hasExpired(date) {
        return new Date(date) < Date.now();
      },
      range(start, end) {
        return _.range(start, end);
      }
    },
    computed: {
      saleRepId() {
        return this.$route.params.rep;
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
