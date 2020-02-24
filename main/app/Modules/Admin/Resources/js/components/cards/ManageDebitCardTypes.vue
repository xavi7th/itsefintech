<template>
  <main>
    <page-header pageTitle="Manage Debit Card Types"></page-header>
    <div class="content">
      <!-- table basic -->
      <div class="card">
        <div class="card-title">
          <button
            type="button"
            class="btn btn-bold btn-pure btn-twitter btn-shadow"
            data-toggle="modal"
            data-target="#modal-card"
            @click="details = {}"
            v-if="$user.isAdmin"
          >Create Debit Card Type</button>
        </div>
        <div class="card-body">
          <table class="table table-bordered table-hover" id="debit-card-types">
            <thead>
              <tr>
                <th>ID</th>
                <th>Card Type</th>
                <th>Amount</th>
                <th>Max Amount</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="debitCardType in debitCardTypes" :key="debitCardType.id">
                <td>{{ debitCardType.id }}</td>
                <td>{{ debitCardType.card_type_name }}</td>
                <td>{{ debitCardType.amount | Naira }}</td>
                <td>{{ debitCardType.max_amount | Naira }}</td>
                <td>
                  <div
                    class="fs-11 btn btn-bold badge badge-success badge-shadow pointer"
                    data-toggle="modal"
                    data-target="#modal-card"
                    @click="showEditDebitCardTypeModal(debitCardType)"
                    v-if="$user.isAdmin"
                  >Edit Card Type</div>
                  <div
                    class="badge badge-info badge-shadow pointer"
                    data-toggle="modal"
                    data-target="#modal-statistics"
                    @click="showDetailsModal(debitCardType)"
                    v-if="$user.isAdmin && false"
                  >Statistics</div>
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
                        <form class="m-25" @submit.prevent="createDebitCardType">
                          <div
                            class="form-group mb-5"
                            :class="{'has-error': errors.has('card_name')}"
                          >
                            <label for="form-full-name">
                              <strong>Card Type Name</strong>
                            </label>
                            <input
                              type="text"
                              class="form-control form-control-pill"
                              id="form-full-name"
                              v-model="details.card_type_name"
                              v-validate="'required'"
                              data-vv-as="debit card type name"
                              name="card_name"
                            />
                            <span>{{ errors.first('card_name') }}</span>
                          </div>
                          <div class="form-group mb-5" :class="{'has-error': errors.has('amount')}">
                            <label for="form-full-name">
                              <strong>Amount</strong>
                            </label>
                            <input
                              type="text"
                              class="form-control form-control-pill"
                              id="form-full-name"
                              v-model="details.amount"
                              v-validate="'required|numeric'"
                              data-vv-as="debit card amount"
                              name="amount"
                            />
                            <span>{{ errors.first('amount') }}</span>
                          </div>

                          <div
                            class="form-group mb-5"
                            :class="{'has-error': errors.has('max_amount')}"
                          >
                            <label for="form-full-name">
                              <strong>Max Amount</strong>
                            </label>
                            <input
                              type="text"
                              class="form-control form-control-pill"
                              id="form-full-name"
                              v-model="details.max_amount"
                              v-validate="'required|numeric'"
                              data-vv-as="maximum debit card funding amount"
                              name="max_amount"
                            />
                            <span>{{ errors.first('max_amount') }}</span>
                          </div>

                          <div class="form-group mt-20">
                            <button
                              type="submit"
                              class="btn btn-success btn-round btn-block btn-bold"
                              v-if="!details.id"
                            >Create</button>
                            <button
                              type="button"
                              class="btn btn-rss btn-round btn-block btn-bold"
                              @click="editDebitCardType"
                              data-dismiss="modal"
                              v-else
                            >Edit</button>
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
        <div class="modal modal-left fade" id="modal-statistics" tabindex="-1">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h4 class="modal-title">{{ debitCardTypeStatistics.card_type_name }}' statistics</h4>
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
                                v-for="(value, property, idx) in debitCardTypeStatistics"
                                :key="idx"
                              >
                                <td v-if="property != 'requester'">{{ slugToString(property) }}</td>
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
    adminViewDebitCardTypes,
    adminEditDebitCardType,
    adminCreateDebitCardType
  } from "@admin-assets/js/config";
  import PreLoader from "@admin-components/misc/PageLoader";
  export default {
    name: "ManageDebitCardTypes",
    data: () => ({
      debitCardTypes: {},
      debitCardTypeStatistics: {},
      sectionLoading: false,
      details: {}
    }),
    components: {
      PreLoader
    },
    created() {
      this.getDebitCardTypes();
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
      showDetailsModal(debitCardTypeStatistics) {
        this.debitCardTypeStatistics = debitCardTypeStatistics;
      },
      showEditDebitCardTypeModal(debitCardTypeDetails) {
        this.details = debitCardTypeDetails;
      },
      getDebitCardTypes() {
        BlockToast.fire({
          text: "loading debit cards..."
        });
        axios
          .get(adminViewDebitCardTypes)
          .then(({ data: { debit_card_types } }) => {
            this.debitCardTypes = debit_card_types;

            if (this.$isDesktop) {
              this.$nextTick(() => {
                $(function() {
                  $("#debit-card-types").DataTable({
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
                  $("#debit-card-types").DataTable({
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

            swal.close();
          });
      },
      createDebitCardType() {
        this.$validator.validateAll().then(result => {
          if (!result) {
            Toast.fire({
              title: "Invalid data! Try again",
              position: "center",
              icon: "error"
            });
          } else {
            BlockToast.fire({
              text: "creating card type..."
            });
            this.sectionLoading = true;

            axios
              .post(adminCreateDebitCardType, {
                ...this.details
              })
              .then(({ status, data: { rsp } }) => {
                if (undefined !== status && status == 201) {
                  this.details = {};
                  this.sectionLoading = false;
                  Toast.fire({
                    title: "Created",
                    text: `Debit Cards of this type can now be created`,
                    icon: "success",
                    position: "center"
                  });
                }
              })
              .catch(err => {
                this.sectionLoading = false;
                if (err.response && err.response.status == 500) {
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

      editDebitCardType(debit_card_type) {
        this.$validator.validateAll().then(result => {
          if (!result) {
            Toast.fire({
              title: "Invalid data! Try again",
              position: "center",
              icon: "error"
            });
          } else {
            BlockToast.fire({
              text: "creating card type..."
            });

            this.sectionLoading = true;
            axios
              .put(adminEditDebitCardType(this.details.id), {
                ...this.details
              })
              .then(({ status, data: { rsp } }) => {
                if (undefined !== status && status == 204) {
                  // debit_card_type = this.details;
                  this.sectionLoading = false;
                  this.details = {};
                  Toast.fire({
                    title: "Edited",
                    text: `The card type has been edited`,
                    icon: "info",
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

      range(start, end) {
        return _.range(start, end);
      }
    },
    computed: {}
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
