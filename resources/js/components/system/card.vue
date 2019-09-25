<template>
    <div class="card_info" :class="className">
      <slot></slot>
    </div>
</template>

<script>

    export default {
        data() {
            return {
            }
        },
        props: [
          'className'
        ]
    }
</script>

<style>
  .card_info {
    position: relative;
    border-radius: 8px;
    box-shadow: 0 1px 2px 0 rgba(0,0,0,0.16);
    border: solid 1px transparent;
    background: #fff;
    margin-bottom: 10px;
    padding: 20px 20px 5px 20px;
  }
</style>