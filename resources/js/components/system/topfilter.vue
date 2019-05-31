<template>
    <div class="top-filter">
      <span class="top-filter__label">{{item.caption}}</span>
      <dropdown-menu icon_after="fa fa-chevron-down">
        <template v-slot:activator>
          {{curItem.name}}
        </template>
          <li><radio-button v-for="it in item.items" :key="it.id" :caption = "it.name" :name="item.name" :value="it.id" @input="onInput($event)" :checked="curId==it.id"></radio-button></li>
      </dropdown-menu>
    </div>
</template>

<script>
  import Dropdown from './Dropdown.vue';
  import radioButton from './radio-button.vue';

    export default {
        data() {
            return {
              curId: this.curValue || 1
            }
        },
        props: [
          'item',
          'curValue'
        ], 
        components: {
         'dropdown-menu': Dropdown,
         'radio-button': radioButton
        },
        computed: {
          curItem() {
            return this.item.items.find((el) => {
              return el.id == this.curId;
            }) || this.item.items[0];
          }
        },
        methods: {
          onInput(e) {
            this.curId = e;
            this.$emit('input', e)
          }
        }
    }
</script>

<style>
  @media (min-width: 992px) {
    .top-filter {
      width: auto;
    }
  }
  .top-filter {
    display: inline-block;
    position: relative;
  }
  .top-filter__label {
    display: inline-block;
    font-size: 14px;
    margin-right: 5px;
    vertical-align: middle;
  }
  .top-filter .menu-dropdown {
    position: static;
    display: inline-block;
  }
  .top-filter .menu-dropdown-target, .top-filter i {
    color: #0d61af;
    transition: .3s;
    text-decoration: none;
    font-size: 14px;
  }
  .top-filter i {
    font-size: 10px;
  }
  .top-filter .menu-dropdown-target:hover, .top-filter .menu-dropdown-target:hover ~ i {
    text-decoration: none;
    color: #fc5808;
  }
  .top-filter .menu-dropdown-items {
    border-radius: 8px;
    box-shadow: 0 8px 12px 0 rgba(0,0,0,0.16);
    border: 1px solid rgba(0,0,0,0.08);
    padding: 8px 0;
    background: #fff;
    transition: opacity .4s;
    z-index: 101;
    position: absolute;
    top: calc(100% + 10px);
    left: 0;
    opacity: 0;
    display: none;
  }
  .top-filter .menu-dropdown-items li {
    padding: 0;
  }
  .top-filter .ui-radio__item {
    cursor: pointer;
    padding-left: 16px;
    padding-right: 16px;
  }
   .top-filter input[type="radio"] {
    opacity: 0;
    margin: 0 0 0 -9999px;
    position: absolute;
    top: 50%;
    height: 0;
  }
  .top-filter+.top-filter {
    margin-left: 30px;
  }
</style>