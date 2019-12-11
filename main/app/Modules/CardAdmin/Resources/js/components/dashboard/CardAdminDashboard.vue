<template>
  <main>
    <page-header pageTitle="Dashboard"></page-header>
    <div class="content">
      <div class="card">
        <div class="card-body">Manage yfjhg</div>
      </div>
    </div>
  </main>
</template>

<script>
  export default {
    name: "AccountantDashboard",
    mounted() {
      this.$emit("page-loaded");
    }
  };
</script>

<style lang="scss" scoped>
</style>
