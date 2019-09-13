<template>
    <label class="ui-radio__item" :class="{'ui-radio__item_list': list}">
      <input type="radio" :class="{'ui-radio__input_list': list}" class="ui-radio__input" :name="name" :value="value" @change="onInput" :checked="checked">
      <span class="ui-radio__content" :class="{'ui-ui-radio__content_list': list}">{{caption}}</span>
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
         'checked',
         'list'
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
    background-color: #E8F2FB;
  }

  .ui-radio__item_list {
    padding-left: 15px;
    transition: .3s;
  }
  .ui-radio__content_list {
    font-size: 14px;
  }
</style>