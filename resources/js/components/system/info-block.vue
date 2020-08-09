<template>
    <div class="info-block">
      <div v-if="header" class="info-block__header">{{header}}</div>
      <slot></slot>
      <span @click="onClick" class="info-block__close"></span>
    </div>
</template>

<script>
 
    export default {
        data() {
            return {
            }
        },
        props: [
          'header'
        ],
        methods: {
          onClick() {
            this.$emit('close');
          }
        }
    }
</script>

<style lang="less">
  .info-block {
    box-shadow: 0 1px 2px 0 rgba(0,0,0,0.16);
    position: relative;
    background-color: #fff7da;
    color: #475d2f;
    border-radius: 8px;
    margin: 10px 0;
    padding: 10px;
    box-sizing: content-box;

    p{
      font-size: 0.9em;
      padding: 5px 0;
    }

    &__header {
      font-weight: bold;
    }
    &__close {
      position: absolute;
      right: 12px;
      top: 12px;
      width: 20px;
      height: 20px;
      cursor: pointer;
      &:before, &:after {
        content: '';
        position: absolute;
        top: 0;
        width: 2px;
        height: 20px;
        cursor: pointer;
        background-color: #8c8c8c;
      }
      &:before {
        left: 10px;
        transform: rotateZ(45deg);
      }
      &:after {
        right: 8px;
        transform: rotateZ(-45deg);
      }
    }
  }
</style>