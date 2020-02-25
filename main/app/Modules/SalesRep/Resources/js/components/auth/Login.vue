<template>
  <div class="auth-split">
    <div class="auth-left">
      <div class="auth-box-wrapper">
        <div class="auth-logo">
          <div class="logo">
            <div class="logo-type logo-type-colored">
              <img src="/img/logowhite.png" alt="CapitalX Logo" class="loader-img" />
            </div>
          </div>
        </div>
        
        <form @submit.prevent="loginDispatch">
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
    <div class="auth-right" style="background-image:url('/img/auth-split.jpg')">
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
    name: "SalesRepAuth",
    mounted() {
      this.$emit("page-loaded");
    },
    data: () => ({
      details: {}
    }),
    methods: {
      loginDispatch() {
        this.$validator.validateAll().then(result => {
          if (!result) {
            Toast.fire({
              title: "Invalid data! Try again",
              position: "center",
              icon: "error"
            });
          } else {
            BlockToast.fire({
              text: "Accessing your dashboard..."
            });

            axios
              .post("/sales-reps/login", { ...this.details })
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
                      icon: "warning"
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
                      icon: "info"
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
                                icon: "success"
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

  .auth-split .auth-right:after {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    box-shadow: -10px 0 30px rgba(103, 3, 29, 0.2);
    // background: linear-gradient(-45deg, #ec9c4f, rgba(51, 129, 181, 0.9), rgba(161, 2, 13, 0.5), #ec9c4f);
    background: linear-gradient(-45deg, #d20707, rgba(0, 0, 0, 0.92), rgba(161, 2, 13, 0.75), #000000);
    background-size: 400% 400%;
    -ms-filter: "progid:DXImageTransform.Microsoft.Alpha(Opacity=85)";
    opacity: 0.85;
    filter: alpha(opacity=85);
    -webkit-animation-name: sliderBg;
    animation-name: sliderBg;
    -webkit-animation-duration: 10s;
    animation-duration: 10s;
    -webkit-animation-timing-function: ease-in-out;
    animation-timing-function: ease-in-out;
    -webkit-animation-iteration-count: infinite;
    animation-iteration-count: infinite;
    -webkit-animation-play-state: running;
    animation-play-state: running;
}

  .auth-left{
    background-color: #121213;
    border-right: 2px solid red;
    color: #fff;
  }

  .btn-primary.btn-shadow {
    box-shadow: 0 3px 10px rgba(235, 27, 27, 0.5);
  }

  .btn-primary {
    background-color: #eb1b1b;
    border-color: #eb1b1b;
  }

  .btn.btn-round {
    border-radius: 3px;
  }

  .form-control {
    border-radius: 3px;
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
