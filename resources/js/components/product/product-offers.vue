<template>
    <div class="products-page__offers" >
      <div class="offer-representation-in-category" :style="styleForHeight">
        <div class="offers-block">
          <div ref="inn" class="inner-block">
            <show-switch type="show-more" :visible="isCollapse===true" @clickSwitch="onSwitch(false)"></show-switch>
            <item-block name="Первый"></item-block>
            <item-block name="Перklkjljвый"></item-block>
            <item-block name="Перjklвый"></item-block>
            <item-block name="Пеljk;kjрвый"></item-block>
            <item-block name="Пеkjlljрвый"></item-block>
            <item-block name="Пеkjljkрвый"></item-block>
            <item-block name="Первый"></item-block>
            <item-block name="Перklkjljвый"></item-block>
            <item-block name="Перjklвый"></item-block>
            <item-block name="Пеljk;kjрвый"></item-block>
           
            <show-switch type="hide-all" :visible="isCollapse===false" @clickSwitch="onSwitch(true)"></show-switch>
          </div>
        </div>
      </div>
    </div>
</template>

<script>
  import { mapGetters } from 'vuex';
  import showSwitch from '../system/show-switch.vue';
  import itemBlock from '../system/item-block.vue';
    export default {
        data() {
            return {
              isCollapse: null
            }
        },
        components: {
          'show-switch': showSwitch,
          'item-block': itemBlock
        },
        computed: {
          ...mapGetters([
            'screenWidth'
          ]),
          styleForHeight() {
            let ht = this.isCollapse===false ? 'auto' : '42px';
            return {'height' : ht}
          }
        },
        methods: {
          onSwitch(mode) {
            this.isCollapse = mode;
          }
        },
        watch: {
          screenWidth() {
            if (this.$refs.inn.offsetHeight>42) {
              if (this.isCollapse===null) {
                this.isCollapse = true;
              }
            } else {
              this.isCollapse = null;
            }
          }
        },
        mounted() {
          if (this.$refs.inn.offsetHeight>42) {
            this.isCollapse = true;
          };
        }
    }
</script>

<style>
  .products-page__offers {
    width: 100%;
    border-bottom: 1px solid #eaeaea;
  }
  .offer-representation-in-category .inner-block {
    padding: 0 20px;
  }
  @media (min-width: 992px){
    .offer-representation-in-category {
      margin: 1em 0;
      overflow: hidden;
    }
  }
  @media (max-width: 991px) {
    .products-page__offers {
        display: none;
    }
  }
</style>