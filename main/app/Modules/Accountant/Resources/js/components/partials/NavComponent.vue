<template>
  <aside class="main-navigation">
    <nav data-simplebar>
      <div class="header">
        <div class="logo">
          <div class="logo-type">
            <router-link :to="{name:'accountant.root'}">
              <img src="/img/logowhite.png" alt="CapitalX Logo" class="loader-img" />
            </router-link>
          </div>
        </div>
      </div>
      <ul class="menu">
        <li class="menu-category">ACCOUNTANT</li>

        <li
          v-for="(item, index) in routes.filter(x => x.path !== '*')"
          :key="index"
          :class="{'nav-sub':!item.name}"
        >
          <router-link :to="item.path" v-if="item.name && !item.meta.skip" class="nav-link">
            <i class="fa fa-home nav-icon" :class="item.meta.iconClass"></i>
            {{item.meta.menuName}}
          </router-link>
          <a href="#" v-else-if="!item.meta.skip">
            <i class="fa fa-desktop nav-icon" v-bind:class="item.meta.iconClass"></i>
            {{item.meta.menuName}}
          </a>
          <ul class="sub-menu" v-if="item.children">
            <li
              v-for="childItem in item.children"
              :key="childItem.name"
              v-show="!childItem.meta.skip"
            >
              <router-link :to="childItem.path" class="nav-link">{{childItem.meta.menuName}}</router-link>
            </li>
          </ul>
        </li>
      </ul>
    </nav>
  </aside>
</template>

<script>
  export default {
    name: "AdminNav",
    mounted() {
      this.$loadScript("/js/admin-nav.js");
      setTimeout(() => {
        $(".menu .nav-link").click(e => {
          $(".header-btn.closed").removeClass("closed");
          $(".main-navigation.open-nav").removeClass("open-nav");
          $(".header-small").removeClass("header-small");
          $("a.active").removeClass("active");
          $(".sub-menu").hide(100);
        });
      }, 1000);
    },
    computed: {
      routes() {
        return this.$router.options.routes;
      }
    }
  };
</script>

<style lang="scss" scoped>
  .main-navigation {
    background-color: #101010;
  }
  .logo-caption {
    color: #24adef;
  }
  .loader-img {
    margin: 55px auto 50px;
    height: 60px;
  }
</style>
