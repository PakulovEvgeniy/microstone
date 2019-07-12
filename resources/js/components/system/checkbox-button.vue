<template>
    <label class="ui-checkbox" :class="{'ui-checkbox_list': list}">
      <span>{{caption}}</span>
      <input type="checkbox" :class="{'ui-checkbox__input_list': list}" class="ui-checkbox__input" :value="value" @input="$emit('input', $event.target.value)" v-model="model">
      <span class="ui-checkbox__box" :class="{'ui-checkbox__box_list': list}"></span>
    </label>
</template>

<script>

    export default {
        data() {
            return {
            }
        },
        props: [
         'caption',
         'name',
         'value',
         'model',
         'list'
        ], 
        components: {

        }
    }
</script>

<style>
  .ui-checkbox {
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    position: relative;
    display: block;
    cursor: pointer;
    font-size: 16px;
    padding: 8px 0 8px 23px;
    color: #333;
  }
  .ui-checkbox_list {
    font-size: 14px;
    padding: 6px 0;
    padding-left: 41px;
    transition: .3s;
    margin: 0;
  }
  .ui-checkbox__box {
    border-radius: 4px;
    position: absolute;
    top: 10px;
    left: 0;
    height: 16px;
    width: 16px;
    box-sizing: border-box;
    background: transparent;
    border: 1px solid #afafaf;
    transition: all 250ms cubic-bezier(0.4, 0, 0.23, 1);
  }
  .ui-checkbox__box_list {
    left: 15px;
    top: 7px;
  }
  .ui-checkbox__input {
    position: absolute;
    opacity: 0;
    top: 50%;
    height: 0;
    margin: 0 !important;
  }
  .ui-checkbox__input:hover+.ui-checkbox__box {
    border-color: #8c8c8c;
  }
  .ui-checkbox__input:checked+.ui-checkbox__box {
    border: 6px solid rgb(29, 113, 184);
    background-color: rgb(29, 113, 184);
    animation: shrink-bounce 200ms cubic-bezier(0.4, 0, 0.23, 1);
  }
  .ui-checkbox__input:checked+.ui-checkbox__box:after {
    -webkit-transform: rotate(45deg);
    -moz-transform: rotate(45deg);
    -ms-transform: rotate(45deg);
    -o-transform: rotate(45deg);
    transform: rotate(45deg);
    position: absolute;
    left: -3px;
    top: 1px;
    width: 3px;
    height: 7px;
    border-right: 2px solid transparent;
    border-bottom: 2px solid transparent;
    content: "";
    box-sizing: content-box;
    transform-origin: 0 100%;
    animation: checkbox-check 125ms 250ms cubic-bezier(0.4, 0, 0.23, 1) forwards;
  }
  .ui-checkbox_list:hover {
    background-color: #fff7da;
  }
  @keyframes shrink-bounce  {
    0%{transform:scale(1)}33%{transform:scale(0.85)}100%{transform:scale(1)}
  }
  @keyframes checkbox-check{
    0%{width:0;height:0;border-color:#fff;transform:translate3d(0, 0, 0) rotate(45deg)}
    33%{width:.2em;height:0;transform:translate3d(0, 0, 0) rotate(45deg)}
    100%{width:.2em;height:.5em;border-color:#fff;transform:translate3d(0, -0.5em, 0) rotate(45deg)}
  }
</style>