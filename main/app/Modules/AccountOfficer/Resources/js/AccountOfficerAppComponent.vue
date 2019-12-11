<template>
  <div class="wrapper" v-if="isAuth">
    <transition name="nav-transition" mode="out-in">
      <router-view @page-loaded="pageLoaded" />
    </transition>
  </div>
  <div class="wrapper" v-else>
    <account-officer-nav></account-officer-nav>
    <account-officer-header v-on:logout-user="logoutUser()" v-if="!is404"></account-officer-header>

    <transition name="fade" :duration="{ enter: 1300, leave: 200 }">
      <pre-loader v-if="isLoading"></pre-loader>
    </transition>
    <transition name="nav-transition" mode="out-in" :duration="{ leave: 600, enter: 600 }">
      <router-view @page-loaded="pageLoaded" @is-loading="toggleLoadState" />
    </transition>

    <account-officer-footer v-if="!is404"></account-officer-footer>
  </div>
</template>

<script>
  import PreLoader from "@admin-components/misc/PageLoader";
  import AccountOfficerNav from "@accountOfficer-components/partials/NavComponent";
  import AccountOfficerHeader from "@accountOfficer-components/partials/HeaderComponent";
  import AccountOfficerFooter from "@admin-components/partials/FooterComponent";

  export default {
    name: "AccountOfficerApp",
    data: () => ({
      freshLoad: true,
      isLoading: true
    }),
    components: {
      AccountOfficerHeader,
      AccountOfficerFooter,
      AccountOfficerNav,
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
        axios.post("/account-officers/logout").then(rsp => {
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
