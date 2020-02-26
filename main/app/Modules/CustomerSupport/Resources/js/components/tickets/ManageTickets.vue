<template>
  <main>
    <page-header pageTitle="Manage Tickets"></page-header>
    <div class="content">
      <!-- table basic -->
      <div class="card">
        <div class="card-title" v-if="$user.isCustomerSupport">
          <button
            type="button"
            class="btn btn-bold btn-pure btn-rss btn-shadow"
            data-toggle="modal"
            data-target="#modal-create-ticket"
          >Create Ticket</button>
        </div>
        <div class="card-body">
          <table class="table table-bordered table-hover" id="supportTicketsTable">
            <thead>
              <tr>
                <th>ID</th>
                <th>Created By</th>
                <th>Date</th>
                <th>Ticket Type</th>
                <th>Ticket Channel</th>
                <th>Status</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="supportTicket in supportTickets" :key="supportTicket.id">
                <td>{{ supportTicket.id }}</td>
                <td>{{ supportTicket.created_by }}</td>
                <td>{{ supportTicket.created_at }}</td>
                <td>{{ supportTicket.ticket_type }}</td>
                <td>{{ supportTicket.channel }}</td>
                <td>{{ supportTicket.status }}</td>
                <td>
                  <div
                    class="fs-11 btn btn-bold badge badge-warning badge-shadow pointer"
                    @click="acceptSupportTicket(supportTicket)"
                    v-if="!supportTicket.is_started && $user.type == supportTicket.department_slug"
                  >Accept Ticket</div>
                  <div
                    class="fs-11 btn btn-bold badge badge-success pointer"
                    @click="markSupportTicketAsResolved(supportTicket)"
                    v-if="supportTicket.is_started && !supportTicket.is_resolved && $user.type == supportTicket.department_slug"
                  >Mark Resolved</div>
                  <div
                    class="fs-11 btn btn-bold badge badge-danger pointer"
                    @click="closeTicket(supportTicket)"
                    v-if="!supportTicket.is_closed && $user.isCustomerSupport"
                  >Close ticket</div>
                  <div
                    class="badge btn btn-bold badge-info badge-shadow pointer"
                    data-toggle="modal"
                    data-target="#modal-details"
                    @click="showModal(supportTicket)"
                  >View Full Details</div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <div class="modal modal-left fade" id="modal-details" tabindex="-1">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h4 class="modal-title">Ticket {{ supportTicketDetails.id }} details</h4>
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
                              <div
                                class="fs-16 fw-500 text-success"
                              >{{ supportTicketDetails.department }}</div>
                              <span class="fs-12 text-light">Ticket Department</span>
                            </div>
                            <div class="flex-sh-0 ln-18">
                              <div
                                class="fs-16 fw-500 text-danger"
                              >{{ supportTicketDetails.started_by || 'Not Started' }}</div>
                              <span class="fs-12 text-light">
                                <!-- <i class="far fa-clock"></i> -->
                                Started By
                              </span>
                            </div>
                            <div class="flex-sh-0 ln-18">
                              <div
                                class="fs-16 fw-500 text-warning"
                              >{{ supportTicketDetails.resolved_by || 'Not Resolved' }}</div>
                              <span class="fs-12 text-light">
                                <!-- <i class="far fa-clock"></i> -->
                                Resolved By
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
                              <tr v-for="(value, property, idx) in supportTicketDetails" :key="idx">
                                <td>{{ slugToString(property) }}</td>
                                <td>
                                  <span v-if="property != 'cards'">{{ value }}</span>
                                  <a :href="value" v-else target="_blank">
                                    <img :src="value" alt class="img-fluid" />
                                  </a>
                                  <div
                                    class="badge badge-danger pointer btn-bold pull-right"
                                    @click="showFullBvnNumber(user)"
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
              </div>
            </div>
          </div>
        </div>

        <div class="modal modal-right fade" id="modal-create-ticket" tabindex="-1">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <!-- <h4 class="modal-title">Raise Support Ticket</h4> -->
                <button type="button" class="close" data-dismiss="modal">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <pre-loader v-if="sectionLoading" class="section-loader"></pre-loader>
                <div class="col-md-12">
                  <div class="card">
                    <div class="card-title flex j-c-between">
                      <h3>Raise Support Ticket</h3>
                    </div>

                    <div class="card-body py-0">
                      <div class="card-row">
                        <form class="m-25" @submit.prevent="createSupportTicket">
                          <div class="row">
                            <div class="col-6">
                              <div
                                class="form-group mb-5"
                                :class="{'has-error': errors.has('ticket_type')}"
                              >
                                <label for="form-ticket-type">
                                  <strong>Ticket Type</strong>
                                </label>
                                <select
                                  class="form-control"
                                  id="form-ticket-type"
                                  name="ticket_type"
                                  v-validate="'required'"
                                  data-vv-as="ticket type"
                                  v-model="details.ticket_type"
                                >
                                  <option :value="null">Ticket Type</option>
                                  <option v-for="n in ['complaint', 'request']" :key="n">{{ n }}</option>
                                </select>
                              </div>
                            </div>

                            <div class="col-6">
                              <div
                                class="form-group mb-5"
                                :class="{'has-error': errors.has('channel')}"
                              >
                                <label for="form-channel">
                                  <strong>Channel</strong>
                                </label>
                                <select
                                  class="form-control"
                                  id="form-channel"
                                  name="channel"
                                  v-validate="'required'"
                                  data-vv-as="channel ticket came through"
                                  v-model="details.channel"
                                >
                                  <option :value="null">Channel</option>
                                  <option
                                    v-for="n in ['phone call', 'email', 'sms', 'whatsapp', 'others']"
                                    :key="n"
                                  >{{ n }}</option>
                                </select>
                              </div>
                            </div>

                            <span class="text-danger">{{ errors.first('ticket_type') }}</span>
                            <span class="text-danger">{{ errors.first('channel') }}</span>
                          </div>

                          <div class="row">
                            <div class="col-12">
                              <div
                                class="form-group mb-5"
                                :class="{'has-error': errors.has('department')}"
                              >
                                <label for="form-year">
                                  <strong>Department</strong>
                                </label>
                                <select
                                  class="form-control"
                                  id="form-year"
                                  name="department"
                                  v-validate="'required'"
                                  data-vv-as="affected department"
                                  v-model="details.department_id"
                                >
                                  <option :value="null">Concerned Department</option>
                                  <option
                                    v-for="department in departments"
                                    :key="department.id"
                                    :value="department.id"
                                  >{{ department.display_name }}</option>
                                </select>
                              </div>
                            </div>

                            <span class="text-danger">{{ errors.first('department') }}</span>
                          </div>

                          <div
                            class="form-group mb-5"
                            :class="{'has-error': errors.has('description')}"
                          >
                            <label for="form-description">
                              <strong>Detailed Description</strong>
                            </label>

                            <textarea
                              class="form-control"
                              id="form-description"
                              rows="7"
                              v-model="details.description"
                              v-validate="'required'"
                              name="description"
                              placeholder="for example: user_email@capitalx.cards complained that... OR user_email@capitalx.cards requests for..."
                            ></textarea>

                            <span>{{ errors.first('description') }}</span>
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
  import { adminViewSupportTickets } from "@admin-assets/js/config";
  import {
    customerSupportCreateSupportTicket,
    adminAcceptSupportTicket,
    adminMarkSupportTicketResolved,
    customerSupportCloseTicket
  } from "@customerSupport-assets/js/config";
  import PreLoader from "@admin-components/misc/PageLoader";
  export default {
    name: "ManageSupportTickets",
    data: () => ({
      supportTickets: [],
      supportTicketDetails: {},
      departments: [],
      sectionLoading: false,
      details: {
        department_id: null,
        ticket_type: null,
        channel: null
      }
    }),
    components: {
      PreLoader
    },
    created() {
      this.getSupportTickets();
    },
    mounted() {
      this.$emit("page-loaded");
    },
    methods: {
      showModal(supportTicketDetails) {
        this.supportTicketDetails = supportTicketDetails;
      },
      slugToString(slug) {
        let words = slug.split("_");

        for (let i = 0; i < words.length; i++) {
          let word = words[i];
          words[i] = word.charAt(0).toUpperCase() + word.slice(1);
        }
        return words.join(" ");
      },
      getSupportTickets() {
        BlockToast.fire({
          text: "loading support tickets..."
        });
        axios
          .get(adminViewSupportTickets)
          .then(({ data: { support_tickets, departments } }) => {
            this.supportTickets = support_tickets;
            this.departments = departments;

            if (this.$isDesktop) {
              this.$nextTick(() => {
                $(function() {
                  $("#supportTicketsTable").DataTable({
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
                  $("#supportTicketsTable").DataTable({
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
      createSupportTicket() {
        this.$validator.validateAll().then(result => {
          if (!result) {
            Toast.fire({
              title: "Invalid data! Try again",
              position: "center",
              icon: "error"
            });
          } else {
            BlockToast.fire({
              text: "creating support ticket..."
            });

            axios
              .post(customerSupportCreateSupportTicket, {
                ...this.details
              })
              .then(({ status, data: support_ticket }) => {
                if (undefined !== status && status == 201) {
                  this.details = {};
                  this.supportTicketDetails = {};
                  this.supportTickets.push(support_ticket);
                  Toast.fire({
                    title: "Created",
                    text: `Members of the relevant department will see it on next login`,
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
      acceptSupportTicket(supportTicketDetails) {
        this.sectionLoading = false;
        BlockToast.fire({
          text: "accepting support ticket..."
        });
        swalPreconfirm
          .fire({
            text:
              "You will become recognised as the one directly responsible for the resolution of this ticket.",
            showCloseButton: true,
            confirmButtonText: "Assign task to me",
            preConfirm: () => {
              return axios
                .put(adminAcceptSupportTicket(supportTicketDetails.id))
                .then(rsp => {
                  if (rsp.status !== 204) {
                    throw new Error(rsp.statusText);
                  }
                  return { rsp: true };
                });
            }
          })
          .then(result => {
            if (result.value) {
              swal
                .fire({
                  title: `Success`,
                  text: "Ticket assigned to you!",
                  icon: "success"
                })
                .then(() => {
                  location.reload();
                });
            }
          });
      },
      markSupportTicketAsResolved(supportTicketDetails) {
        swalPreconfirm
          .fire({
            text:
              "You will become recognised as the one that resolved this ticket.",
            showCloseButton: true,
            confirmButtonText: "Mark resolved",
            preConfirm: () => {
              return axios
                .put(adminMarkSupportTicketResolved(supportTicketDetails.id))
                .then(rsp => {
                  if (rsp.status !== 204) {
                    throw new Error(rsp.statusText);
                  }
                  return { rsp: true };
                });
            }
          })
          .then(result => {
            if (result.value) {
              swal
                .fire({
                  title: `Success`,
                  text: "Ticket marked as resolved by you!",
                  icon: "success"
                })
                .then(() => {
                  location.reload();
                });
            }
          });
      },
      closeTicket(supportTicketDetails) {
        swalPreconfirm
          .fire({
            text:
              "This ticket will become marked as closed and will no longer be viewable by the concerned department.",
            showCloseButton: true,
            confirmButtonText: "yes, close ticket",
            preConfirm: () => {
              return axios
                .put(customerSupportCloseTicket(supportTicketDetails.id))
                .then(rsp => {
                  if (rsp.status !== 204) {
                    throw new Error(rsp.statusText);
                  }
                  return { rsp: true };
                });
            }
          })
          .then(result => {
            if (result.value) {
              swal
                .fire({
                  title: `Success`,
                  text: "Ticket marked as resolved by you!",
                  icon: "success"
                })
                .then(() => {
                  location.reload();
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
