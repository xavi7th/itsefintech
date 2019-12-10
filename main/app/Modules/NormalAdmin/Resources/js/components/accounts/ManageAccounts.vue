<template>
  <main>
    <page-header pageTitle="Manage Admins"></page-header>
    <div class="content">
      <div class="document">
        <!-- table basic -->
        <div class="card">
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
                  <td>{{ user.is_verified ? 'Account Verified' : 'Unverified Account' }}</td>
                  <td>
                    <div
                      class="badge badge-success badge-shadow pointer"
                      data-toggle="modal"
                      data-target="#modal-right"
                      @click="testIt(user)"
                    >View Full Details</div>
                  </td>
                </tr>
              </tbody>
            </table>

            <div class="modal modal-right fade" id="modal-right" tabindex="-1">
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
                              <!-- <div class="flex j-c-between bd bg-light py-30 px-30">
                                <div class="flex-sh-0 ln-18">
                                  <div class="fs-16 fw-500 text-success">122 Sales</div>
                                  <span class="fs-12 text-light">
                                    <i class="far fa-clock"></i> 24 hours
                                  </span>
                                </div>
                                <div class="flex-sh-0 ln-18">
                                  <div class="fs-16 fw-500 text-danger">3 Problem</div>
                                  <span class="fs-12 text-light">
                                    <i class="far fa-clock"></i> 24 hours
                                  </span>
                                </div>
                                <div class="flex-sh-0 ln-18">
                                  <div class="fs-16 fw-500 text-warning">14 Waiting</div>
                                  <span class="fs-12 text-light">
                                    <i class="far fa-clock"></i> 24 hours
                                  </span>
                                </div>
                              </div>-->
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
                                      <span v-if="property != 'user_passport'">{{ value }}</span>
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
                    <!-- <button type="button" class="btn btn-bold btn-pure btn-primary">Save changes</button> -->
                  </div>
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
  import { adminViewUsers } from "@admin-assets/js/config";
  export default {
    name: "ManageAdmins",
    data: () => ({
      users: {},
      userDetails: {}
    }),
    created() {
      axios.get(adminViewUsers).then(({ data: { users } }) => {
        this.users = users;

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
      });
    },
    mounted() {
      this.$emit("page-loaded");
    },
    methods: {
      testIt(userDetails) {
        this.userDetails = userDetails;
      },
      slugToString(slug) {
        let words = slug.split("_");

        for (let i = 0; i < words.length; i++) {
          let word = words[i];
          words[i] = word.charAt(0).toUpperCase() + word.slice(1);
        }
        return words.join(" ");
      }
    }
  };
</script>

<style lang="scss" scoped>
  .modal-right {
    .modal-dialog {
      min-width: 35%;

      .modal-body {
        overflow-y: auto;
      }
    }
  }
</style>
