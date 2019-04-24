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

  .rl-dialog .error {
    background-color: #fed4d5;
    border-radius: 4px;
    margin-bottom: 10px;
    color: #c20000;
    text-align: center;
    padding: 5px 5px 7px 5px;
  }

  .rl-dialog input {
    font-size: 16px;
    width: 444px;
    height: 30px;
    margin-top: 7px;
    margin-bottom: 10px;
    border: 1px solid #ccc;
    border-radius: 4px;
    padding: 0 3px 0 3px;
  }
  .rl-dialog input[type="checkbox"] {
    visibility: hidden;
    width: 30px;
  }
  .rl-dialog *{
    box-sizing:content-box;
  }
</style>