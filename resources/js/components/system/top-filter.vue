<template>
    <div class="top-filter">
      <span class="top-filter__label">{{item.caption}}</span>
      <dropdown-menu @input="active=$event">
        <template v-slot:activator>
          <span>
            {{curItem.name}}
          </span>
          <i class="fa" :class="actClass"></i>
        </template>
          <li v-for="it in item.items" :key="it.id"><radio-button  :caption = "it.name" :name="item.name" :value="it.id" @input="onInput($event)" :checked="curId==it.id"></radio-button></li>
      </dropdown-menu>
    </div>
</template>

<script>
  import Dropdown from './drop-down.vue';
  import radioButton from './radio-button.vue';

    export default {
        data() {
            return {
              active: false
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
          actClass() {
            return !this.active ? 'fa-chevron-down' : 'fa-chevron-up';
          },
          curId() {
            return this.curValue || this.item.items[0].id;
          },
          curItem() {
            return this.item.items.find((el) => {
              return el.id == this.curId;
            }) || this.item.items[0];
          }
        },
        methods: {
          onInput(e) {
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

  .top-filter .menu-dropdown-target:hover, .top-filter .menu-dropdown-target:hover ~ i, .top-filter .menu-dropdown-target:hover i {
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
  @media (max-width: 991px) {
    .top-filter {
      display: flex;
      margin: 0;
    }
    .top-filter__label {
      color: #000;
      font-size: 16px;
      line-height: 56px;
      font-weight: bold;
    }
    .top-filter .menu-dropdown-target {
      line-height: 56px;
      font-size: 16px;
      color: #8c8c8c;
      width: 100%;
    }
    .top-filter i {
      font-size: 14px;
      line-height: 56px;
      float: right;
      color: #8c8c8c;
    }
    .top-filter .menu-dropdown {
      flex-grow: 1;
    }
    .top-filter .menu-dropdown-target:hover, .top-filter .menu-dropdown-target:hover ~ i, .top-filter .menu-dropdown-target:hover i {
      color: #8c8c8c;
    }
    .top-filter+.top-filter {
      border-top: 1px solid #d9d9d9;
      margin-left: 0;
    }
    .top-filter .menu-dropdown-items {
      left: calc(50% - 100px);
    }
    .top-filter .ui-radio__item {
      padding-bottom: 10px;
      padding-top: 10px;
    }
    .top-filter .menu-dropdown-active .menu-dropdown-target, 
      .top-filter .menu-dropdown-active .menu-dropdown-target i {
      color: #1D71B8; 
    }
  }
</style>