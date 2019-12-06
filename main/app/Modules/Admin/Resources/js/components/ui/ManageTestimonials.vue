<template>
  <main>
    <page-header pageTitle="Manage Testimonials"></page-header>
    <div class="content">
      <!-- table basic -->
      <div class="card">
        <div class="card-title">
          <button
            type="button"
            class="btn btn-bold btn-pure btn-twitter btn-shadow"
            data-toggle="modal"
            data-target="#modal-testimonial"
          >Create Testimonial</button>
        </div>
        <div class="card-body">
          <table class="table table-bordered table-hover" id="testimonialTable">
            <thead>
              <tr>
                <th>ID</th>
                <th>Actions</th>
                <th>Full Name</th>
                <th>Location</th>
                <th>Image</th>
                <th>Testimonial</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="testimonial in testimonials" :key="testimonial.id">
                <td class="pointer">{{ testimonial.id }}</td>
                <td>
                  <button class="badge badge-danger" @click="deleteTestimonial(testimonial)">Delete</button>
                </td>
                <td>{{ testimonial.name }}</td>
                <td>{{ testimonial.city }}, {{ testimonial.country }}</td>
                <td>
                  <a :href="testimonial.img" target="_blank">
                    <img :src="testimonial.img" :alt="`${testimonial.name}'s image'`" height="100" />
                  </a>
                </td>
                <td class="ellipsis">{{ testimonial.testimonial }}</td>
              </tr>
            </tbody>
          </table>

          <div class="modal modal-right fade" id="modal-testimonial" tabindex="-1">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h4 class="modal-title">Enter Testimonial Details</h4>
                  <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body">
                  <pre-loader v-if="sectionLoading" class="section-loader"></pre-loader>
                  <form class="m-25">
                    <div class="form-group mb-5" :class="{'has-error': errors.has('name')}">
                      <div class="form-group">
                        <input
                          type="file"
                          ref="img"
                          v-validate="'required'"
                          @change="attachFile"
                          id="form-img"
                          name="img"
                          accept="image/*"
                        />
                        <label for="form-img">
                          <strong>{{ fileUploadName }}</strong>
                        </label>
                        <span>{{ errors.first('name') }}</span>
                      </div>
                    </div>
                    <div class="form-group mb-5" :class="{'has-error': errors.has('name')}">
                      <label for="form-full-name">
                        <strong>Full Name</strong>
                      </label>
                      <input
                        type="text"
                        class="form-control form-control-pill"
                        id="form-full-name"
                        v-model="details.name"
                        v-validate="'required'"
                        name="name"
                      />
                      <span>{{ errors.first('name') }}</span>
                    </div>
                    <div class="form-group mb-5" :class="{'has-error': errors.has('city')}">
                      <label for="form-mail">
                        <strong>City</strong>
                      </label>
                      <input
                        type="text"
                        class="form-control form-control-pill"
                        id="form-mail"
                        v-model="details.city"
                        v-validate="'required'"
                        name="city"
                      />
                      <span>{{ errors.first('city') }}</span>
                    </div>

                    <div class="form-group mb-5" :class="{'has-error': errors.has('country')}">
                      <label for="form-mail">
                        <strong>Country</strong>
                      </label>
                      <input
                        type="text"
                        class="form-control form-control-pill"
                        id="form-mail"
                        v-model="details.country"
                        v-validate="'required'"
                        name="country"
                      />
                      <span>{{ errors.first('country') }}</span>
                    </div>

                    <div class="form-group mb-5" :class="{'has-error': errors.has('testimonial')}">
                      <label for="form-testimonial">
                        <strong>Testimonial</strong>
                      </label>
                      <textarea
                        name="testimonial"
                        class="form-control form-control-pill"
                        id="form-testimonial"
                        v-model="details.testimonial"
                        v-validate="'required'"
                      ></textarea>
                      <span>{{ errors.first('testimonial') }}</span>
                    </div>

                    <div class="form-group mt-20">
                      <button
                        type="button"
                        class="btn btn-success btn-round btn-block"
                        data-dismiss="modal"
                        @click="createTestimonial"
                      >Create</button>
                    </div>
                  </form>
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
  import {
    adminViewTestimonials,
    adminCreateTestimonial,
    adminDeleteTestimonial
  } from "@admin-assets/js/config";
  import PreLoader from "@admin-components/misc/PageLoader";
  export default {
    name: "ManageTestimonials",
    data: () => ({
      testimonials: [],
      testimonialDetails: {},
      sectionLoading: false,
      details: {},
      fileUploadName: "Upload user's passport"
    }),
    components: {
      PreLoader
    },
    created() {
      axios.get(adminViewTestimonials).then(({ data: { testimonials } }) => {
        this.testimonials = testimonials;

        this.$nextTick(() => {
          $(function() {
            $("#testimonialTable").DataTable({
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
      attachFile() {
        this.fileUploadName = this.$refs.img.files[0].name;

        this.details.user_img = this.$refs.img.files[0];
      },
      createTestimonial() {
        this.$validator.validateAll().then(result => {
          if (!result) {
            Toast.fire({
              title: "Invalid data! Try again",
              position: "center",
              type: "error"
            });
          } else {
            BlockToast.fire({
              text: "Creating testimonial..."
            });
            let formData = new FormData();

            _.forEach(this.details, (val, key) => {
              formData.append(key, val);
            });

            axios
              .post(adminCreateTestimonial, formData, {
                headers: {
                  "Content-Type": "multipart/form-data"
                }
              })
              .then(({ status, data: { rsp } }) => {
                console.log(rsp);

                if (undefined !== status && status == 201) {
                  this.details = {};
                  this.testimonials.unshift(rsp);
                  Toast.fire({
                    title: "Created",
                    text: `Testimonial created`,
                    type: "success",
                    position: "center"
                  });
                }
              })
              .catch(err => {
                if (err.response.status == 500) {
                  swal.fire({
                    title: "Error",
                    text: `Something went wrong on server. Creation not successful.`,
                    type: "error"
                  });
                }
              });
          }
        });
      },
      deleteTestimonial(testimonial) {
        axios
          .delete(adminDeleteTestimonial(testimonial.id))
          .then(({ status }) => {
            if (status == 204) {
              let removed = this.testimonials.indexOf(testimonial);
              if (removed != -1) {
                /* A match was found */
                this.testimonials.splice(removed, 1);
              }
              Toast.fire({
                title: "Deleted!",
                text: "testimonial was successfully deleted",
                position: "center"
              });
            } else {
              Toast.fire({
                title: "Failed!",
                text: "We were unable to delete the testimonial",
                position: "center",
                type: "error"
              });
            }
          });
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

  .ellipsis {
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
  }

  [type="file"] {
    height: 0;
    overflow: hidden;
    width: 0;

    &#file {
      border: none;
      border-radius: 0;
      padding: 0;
    }
  }

  [type="file"] + label {
    background: #00aced;
    border: 1px solid #c0c0c0;
    border-radius: 40px;
    color: #fff;
    cursor: pointer;
    display: inline-block;
    font-family: "Poppins", sans-serif;
    font-size: inherit;
    font-weight: 600;
    margin-bottom: 1rem;
    outline: none;
    padding: 0.8rem 20px;
    position: relative;
    transition: all 0.3s;
    vertical-align: middle;

    &:hover {
      background-color: darken(#f15d22, 10%);
    }

    &.btn-1 {
      background-color: #f79159;
      box-shadow: 0 6px darken(#f79159, 10%);
      transition: none;

      &:hover {
        box-shadow: 0 4px darken(#f79159, 10%);
        top: 2px;
      }
    }

    &.btn-2 {
      white-space: nowrap;
      text-overflow: ellipsis;
      background-color: #fff;
      background-image: url("data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%22292.4%22%20height%3D%22292.4%22%3E%3Cpath%20fill%3D%22%23007CB2%22%20d%3D%22M287%2069.4a17.6%2017.6%200%200%200-13-5.4H18.4c-5%200-9.3%201.8-12.9%205.4A17.6%2017.6%200%200%200%200%2082.2c0%205%201.8%209.3%205.4%2012.9l128%20127.9c3.6%203.6%207.8%205.4%2012.8%205.4s9.2-1.8%2012.8-5.4L287%2095c3.5-3.5%205.4-7.8%205.4-12.8%200-5-1.9-9.2-5.5-12.8z%22%2F%3E%3C%2Fsvg%3E"),
        linear-gradient(to bottom, #ffffff 0%, #e5e5e5 100%);
      color: #888;
      border-radius: 50px;
      overflow: hidden;
      width: 100%;
      text-align: left;
      padding-left: 20px;
      font-weight: normal;
      text-transform: capitalize;

      &::before {
        color: #fff;

        // content: "\f382";
        // font-family: "Font Awesome 5 Pro";
        font-size: 100%;
        height: 100%;
        right: 130%;
        line-height: 3.3;
        position: absolute;
        top: 0px;
        transition: all 0.3s;
      }

      &:hover {
        background-color: darken(#fff, 5%);
        background-image: url("data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%22292.4%22%20height%3D%22292.4%22%3E%3Cpath%20fill%3D%22%23007CB2%22%20d%3D%22M287%2069.4a17.6%2017.6%200%200%200-13-5.4H18.4c-5%200-9.3%201.8-12.9%205.4A17.6%2017.6%200%200%200%200%2082.2c0%205%201.8%209.3%205.4%2012.9l128%20127.9c3.6%203.6%207.8%205.4%2012.8%205.4s9.2-1.8%2012.8-5.4L287%2095c3.5-3.5%205.4-7.8%205.4-12.8%200-5-1.9-9.2-5.5-12.8z%22%2F%3E%3C%2Fsvg%3E"),
          linear-gradient(to bottom, #e5e5e5 0%, #ffffff 100%);

        &::before {
          right: 75%;
        }
      }
    }
  }
</style>
