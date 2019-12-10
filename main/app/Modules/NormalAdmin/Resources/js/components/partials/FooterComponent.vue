<template>
  <footer>©2019 Orion Tech Inc. All Rights Reserved.</footer>
</template>

<script>
  export default {};
</script>

<style scoped lang="scss">
  footer {
    position: fixed;
    bottom: 5px;
    text-align: center;
    right: 0;
    left: 260px;
    color: #777;

    @media (max-width: 767px) {
      left: 0;
    }
  }
</style>
