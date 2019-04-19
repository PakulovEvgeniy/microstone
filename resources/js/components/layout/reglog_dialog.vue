<template>
    <div class="rl-dialog">
      <slot></slot>
    </div>
</template>

<script>
    export default {
        data() {
            return {
            }
        }
    }
</script>

<style>
  .rl-dialog {
    border: 2px solid #ccc;
    border-radius: 15px;
    width: 530px;
    margin: 15px auto;
    background-color: #fff;
    padding-bottom: 15px;
    position: relative;
    min-height: 100px;
  }
</style>