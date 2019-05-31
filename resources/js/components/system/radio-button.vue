<template>
    <label class="ui-radio__item">
      <input type="radio" class="ui-radio__input" :name="name" :value="value" @input="$emit('input', $event.target.value)" :checked="checked">
      <span class="ui-radio__content">{{caption}}</span>
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
         'checked'
        ], 
        components: {

        }
    }
</script>

<style>
  .ui-radio__item {
      display: block;
      margin: 0;
      padding: 5px 0;
      position: relative;
  }
  .ui-radio__content {
    position: relative;
    cursor: pointer;
    font-size: 16px;
    padding-left: 23px;
  }
  .ui-radio__content:before {
    position: absolute;
    top: 0px;
    display: block;
    content: '';
    width: 16px;
    height: 16px;
    border: 1px solid #afafaf;
    border-radius: 50%;
    transition: all 200ms;
    box-sizing: border-box;
    left: 0;
  }
  input[type="radio"]:checked+.ui-radio__content:before {
    border: 5px solid rgb(29, 113, 184);
  }
  .ui-radio__item:hover {
    background-color: #fff7da;
  }
</style>