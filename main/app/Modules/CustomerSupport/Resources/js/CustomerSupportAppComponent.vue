<template>
  <div class="wrapper" v-if="isAuth">
    <transition name="nav-transition" mode="out-in">
      <router-view @page-loaded="pageLoaded" />
    </transition>
  </div>
  <div class="wrapper" v-else>
    <customer-support-nav></customer-support-nav>
    <customer-support-header v-on:logout-user="logoutUser()" v-if="!is404"></customer-support-header>

    <transition name="fade" :duration="{ enter: 1300, leave: 200 }">
      <pre-loader v-if="isLoading"></pre-loader>
    </transition>
    <transition name="nav-transition" mode="out-in" :duration="{ leave: 600, enter: 600 }">
      <router-view @page-loaded="pageLoaded" @is-loading="toggleLoadState" />
    </transition>

    <customer-support-footer v-if="!is404"></customer-support-footer>
  </div>
</template>

<script>
  import PreLoader from "@admin-components/misc/PageLoader";
  import CustomerSupportNav from "@customerSupport-components/partials/NavComponent";
  import CustomerSupportHeader from "@customerSupport-components/partials/HeaderComponent";
  import CustomerSupportFooter from "@admin-components/partials/FooterComponent";

  export default {
    name: "CustomerSupportApp",
    data: () => ({
      freshLoad: true,
      isLoading: true
    }),
    components: {
      CustomerSupportHeader,
      CustomerSupportFooter,
      CustomerSupportNav,
      PreLoader
    },
    computed: {
      isAuth() {
        return this.$route.name === null || this.$route.path.match("login");
      },
      is404() {
        return this.$route.name
          ? this.$route.name.match("site.error")
            ? true
            : false
          : false;
      }
    },
    methods: {
      logoutUser(msg = "Logging you out....") {
        BlockToast.fire({
          text: msg
        });
        axios.post("/card-admins/logout").then(rsp => {
          location.reload();
        });
      },
      toggleLoadState() {
        this.isLoading = true;
      },
      pageLoaded() {
        $(".preloader").fadeOut(300);
        this.isLoading = false;
      }
    }
  };
</script>

<style lang="scss">
  @import "~@admin-assets/sass/main";
</style>
