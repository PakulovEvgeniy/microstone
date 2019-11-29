<template>
    <div class="n-catalog-product__avails">
      <div class="order-avail-wrap">
        <span class="avail-text">
          <span class="available">Доступно:</span>
          <a v-show="stock>0" class="avail-text__link ui-link ui-link_blue ui-link_pseudolink">В наличии</a>
          <a class="avail-text__link ui-link ui-link_blue ui-link_pseudolink">Под заказ</a>
        </span>
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
          'stock'
        ],
        computed: {
          
        },
        methods: {
          
        } 
    }
</script>

<style lang="less">
  .order-avail-wrap {
    font-size: 13px;
    line-height: 13px;
  }
  .order-avail-wrap .avail-text .available {
    display: inline-block;
    margin-right: 5px;
    line-height: 15px;
    overflow: hidden;
  }
  .order-avail-wrap .avail-text__link {
    display: inline-block;
    line-height: 13px;
    overflow: hidden;
    position: relative;
    text-transform: lowercase;
    margin-right: 5px;
  }
  .avail-text a:last-child {
    margin-right: 0;
  }
</style>