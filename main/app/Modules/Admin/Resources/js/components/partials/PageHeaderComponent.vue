<template>
  <div class="main-header">
    <div class="header-info">
      <h1 class="title">{{ pageTitle }}</h1>
    </div>
    <!-- <div class="header-menu">
        <ul>
          <li class="active">
            <a href="table-basic.html">Basic</a>
          </li>
          <li>
            <a href="table-data.html">Data Table</a>
          </li>
        </ul>
    </div>-->
  </div>
</template>

<script>
  export default {
    name: "PageHeader",
    props: {
      pageTitle: {
        required: true,
        type: String
      }
    }
  };
</script>

<style lang="scss" scoped>
</style>
