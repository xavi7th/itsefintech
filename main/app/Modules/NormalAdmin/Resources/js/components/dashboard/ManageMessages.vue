<template>
  <main>
    <page-header pageTitle="Dashboard"></page-header>
    <div class="content">
      <div class="card">
        <div class="card-body">Manage Messages</div>
      </div>
    </div>
  </main>
</template>
<script>
  import PageHeader from "@admin-components/partials/PageHeaderComponent";
  export default {
    name: "ManageMessages",
    mounted() {
      this.$emit("page-loaded");
    }
  };
</script>


<style lang="scss" scoped>
</style>
