<template>
  <div class="ui-toggle">
    <label class="ui-toggle__switch">
      <input type="checkbox" :value="value" :checked="checked" @change="onInput($event)">
      <span class="ui-toggle__slider">
        {{caption}}
      </span>
    </label>
  </div>
</template>

<script>

    export default {
        data() {
            return {
            }
        },
        props: [
         'caption',
         'value',
         'checked'
        ], 
        components: {

        },
        methods: {
          onInput(e) {
            this.$emit('input', e.target)
          }
        }
    }
</script>

<style lang="less">
@import '../../../less/vars.less';
  .ui-toggle {
    &__switch {
      position: relative;
      display: inline-block;
      font-weight: normal;
      input {
        display: none;
        &:checked {
          + .ui-toggle__slider::before {
            transform: translateX(16px);
          }
          + .ui-toggle__slider::after {
            background-color: @main-color;
          }
        }
      }
    }
    &__slider {
      cursor: pointer;
      display: inline-block;
      min-height: 20px;
      padding-left: 52px;
      position: relative;
      line-height: 20px;
      vertical-align: top;
      &::before {
        position: absolute;
        content: "";
        height: 16px;
        width: 16px;
        left: 2px;
        top: 2px;
        background-color: #fff;
        transition: .4s;
        border-radius: 50%;
        z-index: 99;
      }
      &::after {
        height: 20px;
        position: absolute;
        content: "";
        top: 0;
        left: 0;
        right: 0;
        width: 36px;
        background-color: #ccc;
        transition: .4s;
        border-radius: 34px;
      }
    }
  }
</style>