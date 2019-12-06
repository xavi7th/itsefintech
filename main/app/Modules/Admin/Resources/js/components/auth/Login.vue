<template>
  <div class="auth-split">
    <div class="auth-left">
      <div class="auth-box-wrapper">
        <div class="auth-logo">
          <div class="logo">
            <div class="logo-type logo-type-colored">
              <img src="/img/logo-round-small.png" alt="Amju Unique Logo" class="loader-img" />
              <a href="/">
                <span>
                  amju
                  <span>unique</span>
                </span>
              </a>
            </div>
          </div>
        </div>
        <div class="fs-18 fw-600 text-center mb-30 text-title">Log In</div>
        <form @submit.prevent="loginAdmin">
          <div class="form-group mb-20" :class="{'has-error': errors.has('email')}">
            <label for="form-mail">
              <strong>E-Mail</strong>
            </label>
            <input
              type="text"
              class="form-control form-control-pill"
              id="form-mail"
              v-model="details.email"
              v-validate="'required|email'"
              name="email"
            />
            <span>{{ errors.first('email') }}</span>
          </div>
          <div class="form-group mb-20" :class="{'has-error': errors.has('password')}">
            <label for="form-pass">
              <strong>Password</strong>
            </label>
            <input
              type="password"
              class="form-control form-control-pill"
              id="form-pass"
              v-model="details.password"
              v-validate="'required'"
              name="password"
            />
            <span>{{ errors.first('password') }}</span>
          </div>

          <div class="form-group flex j-c-center mt-30">
            <button class="btn btn-primary btn-shadow btn-round">Log In</button>
          </div>
        </form>
      </div>
    </div>
    <div class="auth-right" style="background-image:url('/img/amju-building.jpg')">
      <div class="auth-content">
        <div class="auth-right__caption">
          <h1>Admin Panel</h1>
          <p>
            Unauthorized attempts to access information or change information on these pages are strictly prohibited and
            are punishable under the Computer Fraud and Abuse Act of 2010 and the Private Information Infrastructure Protection Act.
          </p>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  export default {
    name: "AdminAuth",
    mounted() {
      this.$emit("page-loaded");
    },
    data: () => ({
      details: {}
    }),
    methods: {
      loginAdmin() {
        this.$validator.validateAll().then(result => {
          if (!result) {
            Toast.fire({
              title: "Invalid data! Try again",
              position: "center",
              type: "error"
            });
          } else {
            BlockToast.fire({
              text: "Accessing your dashboard..."
            });

            axios
              .post("/admin-panel/login", { ...this.details })
              .then(rsp => {
                if (undefined !== rsp && rsp.status == 202) {
                  swal.close();
                  sessionStorage.clear();
                  location.reload();
                } else if (undefined !== rsp && rsp.status == 205) {
                  swal
                    .fire({
                      title: "Suspended Account",
                      text: rsp.data.msg,
                      type: "warning"
                    })
                    .then(() => {
                      location.reload();
                    });
                }
              })
              .catch(err => {
                if (err.response.status == 416) {
                  swal
                    .fire({
                      title: "Unverified",
                      text: `This seems to be your first login. You need to supply a password`,
                      type: "info"
                    })
                    .then(() => {
                      swal
                        .fire({
                          title: "Enter a password",
                          input: "text",
                          inputAttributes: {
                            autocapitalize: "off"
                          },
                          showCancelButton: true,
                          confirmButtonText: "Set Password",
                          showLoaderOnConfirm: true,
                          preConfirm: pw => {
                            return axios
                              .post("first-time", {
                                pw,
                                email: this.details.email
                              })
                              .then(response => {
                                if (response.status !== 204) {
                                  throw new Error(response.statusText);
                                }
                                return { rsp: true };
                              })
                              .catch(error => {
                                swal.showValidationMessage(
                                  `Request failed: ${error}`
                                );
                              });
                          },
                          allowOutsideClick: () => !swal.isLoading()
                        })
                        .then(result => {
                          if (result.value) {
                            swal
                              .fire({
                                title: `Success`,
                                text: "Password set successfully!",
                                type: "success"
                              })
                              .then(() => {
                                location.reload();
                              });
                          }
                        });
                    });
                }
              });
          }
        });
      }
    }
  };
</script>

<style lang="scss" scoped>
  .auth-right {
    background-size: cover;
    background-repeat: no-repeat;
    background-position: center;

    .auth-right__caption {
      font-size: larger;
    }
  }
  .logo .logo-type-colored a span {
    text-transform: capitalize;
    color: #1b97eb;

    & > span {
      color: #f90a48;
    }
  }

  .auth-split .auth-left button {
    font-weight: bold;
  }

  .btn-primary.btn-shadow {
    box-shadow: 0 3px 10px rgba(27, 151, 235, 0.5);
  }

  .btn-primary {
    background-color: #1b97eb;
    border-color: #1b97eb;
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
