<template>
  <div class="main">
    <div class="flex a-i-center h-100">
      <div class="loader-ripple">
        <div></div>
        <div></div>
        <div></div>
        <div></div>
      </div>
    </div>
  </div>
</template>

<script>
  export default {};
</script>

<style lang="scss" scoped>
  .main {
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: #fff;
    position: absolute;
    right: 0;
    left: 0;
    z-index: 1;
    padding-top: 70px;
    padding-bottom: 20px;
    margin-left: 260px;
  }
</style>
