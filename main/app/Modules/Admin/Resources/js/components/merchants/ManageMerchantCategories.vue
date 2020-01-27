<template>
  <main>
    <page-header pageTitle="Manage Merchant Categories"></page-header>
    <div class="content">
      <!-- table basic -->
      <div class="card">
        <div class="card-title">
          <button
            type="button"
            class="btn btn-bold btn-pure btn-twitter btn-shadow"
            data-toggle="modal"
            data-target="#modal-merchant-category"
            @click="details = {}"
          >Create Merchant Category</button>
        </div>
        <div class="card-body">
          <table class="table table-bordered table-hover" id="merchant-categories">
            <thead>
              <tr>
                <th>ID</th>
                <th>Merchant Category</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="merchantCategory in merchantCategories" :key="merchantCategory.id">
                <td>{{ merchantCategory.id }}</td>
                <td>{{ merchantCategory.name }}</td>
                <td>
                  <div
                    class="fs-11 btn btn-bold badge badge-success badge-shadow pointer"
                    data-toggle="modal"
                    data-target="#modal-merchant-category"
                    @click="showEditMerchantCategoryModal(merchantCategory)"
                  >Edit Merchant Category</div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
        <div class="modal modal-right fade" id="modal-merchant-category" tabindex="-1">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <!-- <h4 class="modal-title">Add Merchant Category</h4> -->
                <button type="button" class="close" data-dismiss="modal">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <pre-loader v-if="sectionLoading" class="section-loader"></pre-loader>
                <div class="col-md-12">
                  <div class="card">
                    <div class="card-title flex j-c-between">
                      <h3>Add New Merchant Category</h3>
                    </div>

                    <div class="card-body py-0">
                      <div class="card-row">
                        <form class="m-25" @submit.prevent="createMerchantCategory">
                          <div
                            class="form-group mb-5"
                            :class="{'has-error': errors.has('merchant_category_name')}"
                          >
                            <label for="form-full-name">
                              <strong>Merchant Category Name</strong>
                            </label>
                            <input
                              type="text"
                              class="form-control form-control-pill"
                              id="form-full-name"
                              v-model="details.name"
                              v-validate="'required'"
                              data-vv-as="merchant category name"
                              name="merchant_category_name"
                            />
                            <span>{{ errors.first('merchant_category_name') }}</span>
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
                              @click="editMerchantCategory"
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
      </div>
    </div>
  </main>
</template>

<script>
  import {
    adminViewMerchantCategories,
    adminEditMerchantCategory,
    adminCreateMerchantCategory
  } from "@admin-assets/js/config";
  import PreLoader from "@admin-components/misc/PageLoader";
  export default {
    name: "ManageMerchantCategories",
    data: () => ({
      merchantCategories: [],
      sectionLoading: false,
      details: {}
    }),
    components: {
      PreLoader
    },
    created() {
      this.getMerchantCategories();
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
      showEditMerchantCategoryModal(merchantCategoryDetails) {
        this.details = merchantCategoryDetails;
      },
      getMerchantCategories() {
        BlockToast.fire({
          text: "loading merchant categories..."
        });
        axios
          .get(adminViewMerchantCategories)
          .then(({ data: { merchant_categories } }) => {
            this.merchantCategories = merchant_categories;

            if (this.$isDesktop) {
              this.$nextTick(() => {
                $(function() {
                  $("#merchant-categories").DataTable({
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
                  $("#merchant-categories").DataTable({
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
      createMerchantCategory() {
        this.$validator.validateAll().then(result => {
          if (!result) {
            Toast.fire({
              title: "Invalid data! Try again",
              position: "center",
              icon: "error"
            });
          } else {
            BlockToast.fire({
              text: "creating merchant category..."
            });
            this.sectionLoading = true;

            axios
              .post(adminCreateMerchantCategory, {
                ...this.details
              })
              .then(({ status, data: { merchant_category } }) => {
                if (undefined !== status && status == 201) {
                  this.details = {};
                  this.merchantCategories.push(merchant_category);
                  this.sectionLoading = false;
                  Toast.fire({
                    title: "Created",
                    text: `Merchants of this category can now be created`,
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

      editMerchantCategory(merchant_category) {
        this.$validator.validateAll().then(result => {
          if (!result) {
            Toast.fire({
              title: "Invalid data! Try again",
              position: "center",
              icon: "error"
            });
          } else {
            BlockToast.fire({
              text: "creating merchant category..."
            });

            this.sectionLoading = true;
            axios
              .put(adminEditMerchantCategory(this.details.id), {
                ...this.details
              })
              .then(({ status, data: { rsp } }) => {
                if (undefined !== status && status == 204) {
                  // merchant_category = this.details;
                  this.sectionLoading = false;
                  this.details = {};
                  Toast.fire({
                    title: "Edited",
                    text: `The merchant category has been edited`,
                    icon: "info",
                    position: "center"
                  });
                }
              })
              .catch(err => {
                if (err.response.status == 500) {
                  swal.fire({
                    title: "Error",
                    text: `Something went wrong on server. Editing not successful.`,
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
