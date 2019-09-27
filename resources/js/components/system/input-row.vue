<template>
    <div class="input-row">
      <div class="input-row__container">
        <input class="input-row__input" 
          :type="inputType" 
          :name="inputName"
          :id = "inputId"
          :required="inputRequired"
        >
        <label :for="inputId" class="input-row__label">{{label}}</label>
        <i class="fa fa-check"></i>
      </div>
    </div>
</template>

<script>
 
    export default {
        data() {
            return {
            }
        },
        props: [
          'inputType',
          'inputName',
          'inputId',
          'inputRequired',
          'label'
        ],
        methods: {
        }
    }
</script>

<style lang="less">
  .input-row {
    visibility: visible !important;
    max-width: 300px;
    margin-bottom: 20px;
    width: 100%;
    line-height: 1.42857;
    &__container {
      border-radius: 8px;
      display: inline-block;
      position: relative;
      width: 100%;
      height: 54px;
      background: #fff;
      border: 1px solid #e5e5e5;
      overflow: hidden;
      box-sizing: border-box;
    }
    &__input {
      position: absolute;
      top: 0;
      left: 0;
      padding: 18px 0 0 20px;
      height: 100%;
      width: 100%;
      outline: none;
      background: transparent;
      color: #4e4e4e;
      border: none;
      font-weight: bold;
      z-index: 9;
      box-sizing: border-box;
      font-size: 16px;
      font-family: inherit;
      line-height: inherit;
    }
  }
</style>