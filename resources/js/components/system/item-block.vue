<template>
    <div class="item-block collapsible" :class="{'is-active' : active}">
      <a @click="onClick">
        {{name}}
        <i v-if="active" class="fa fa-times leave-category"></i>
      </a>
    </div>
</template>

<script>
 
    export default {
        data() {
            return {
            }
        },
        props: [
          'name',
          'active'
        ],
        methods: {
          onClick() {
            this.$emit('clickOffer');
          }
        }
    }
</script>

<style>
  .item-block {
    border-radius: 15px;
    background-color: #f9f9f9;
    border: 1px solid #d8d8d8;
    display: inline-block;
    font-size: 14px;
    line-height: 30px;
    margin: 5px 10px 5px 0;
    padding: 0 12px;
    text-align: center;
  }
  .item-block a {
    color: #333;
    transition: .3s;
    text-decoration: none;
  }
  .item-block a:hover {
    text-decoration: none;
    color: #fc5808;
  }
  .item-block.is-active {
    font-weight: bold;
  }
  .item-block.is-active .leave-category {
    margin-left: 5px;
    display: inline-block;
    color: #8c8c8c;
  }
</style>