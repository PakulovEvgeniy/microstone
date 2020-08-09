<template>
    <div class="order-blockcart">
      <div class="order-blockcart__price-block">
        <div v-show="qtyStockBuy>0" class="order-blockcart__price-stock">
          <span>В наличии</span><br>
          <span>{{priceViz}}<i class="fa fa-rub"></i></span>
          <span> * {{qtyStockBuy}} шт.</span>
        </div>
        <div v-show="qtyOrderBuy>0" class="order-blockcart__price-order">
          <span>Под заказ<sup v-if="orderDiscount">*</sup></span><br>
          <span>{{priceVizOrder}}<i class="fa fa-rub"></i></span>
          <span> * {{qtyOrderBuy}} шт.</span>
        </div>
      </div>
      
      <div class="order-blockcart__spin-block"">
        <qty-spinner v-model="ordQty"></qty-spinner>
        <ul v-if="listDiscount.length" class="order-blockcart__offer-items">
          <li v-for="(el, ind) in listDiscount" :key="ind">{{el.text}}<i class="fa fa-rub"></i></li>
        </ul>
      </div>

      <div class="order-blockcart__price">{{totalPrice}}<i class="fa fa-rub"></i></div>
    </div>
</template>

<script>
  import qtySpinner from './qty-spinner.vue';
  import { mapGetters } from 'vuex';

    export default {
        data() {
            return {

            }
        },
        components: {
          qtySpinner
        },
        props: [
          'element'
        ],
        computed: {
          ...mapGetters([
            'settings'
          ]),
          product() {
            return this.element.product;
          },
          qty() {
            return this.element.qty;
          },
          curCharact() {
            return this.element.charactItem;
          },
          orderDiscount() {
            return +this.settings.order_discount;
          },
          qtyStockBuy() {
            return this.element.qtyStockBuy;
          },
          qtyOrderBuy() {
            return this.element.qtyOrderBuy;
          },
          listDiscount() {
            return this.element.listDiscount;
          },
          priceViz() {
            return this.element.priceViz;
          },
          priceVizOrder() {
            return this.element.priceVizOrder;
          },
          ordQty: {
            get () {
              return this.qty;
            },
            set (value) {
              //this.$emit('change', value);
              this.$store.dispatch('editToCart', [{id: this.product.id, characteristic: this.product.have_charact ? this.curCharact.id : '', qty: value}]);
            }
          },
          totalPrice() {
            return this.element.totalPrice;
          }
        },
        methods: {
          
        } 
    }
</script>

<style lang="less">
@import '../../../../less/smart-grid.less';

.order-blockcart {
  .row-flex();
  align-items: center;
  > div {
    .col();
  }
  &__price-block {
    .size(9);
    font-size: 14px;
    span {
      i {
        font-size: 13px;
        margin-left: 3px;
      }
    }
  }
  &__spin-block {
    .size(9);
    ul {
      margin-top: 5px;
      li {
        color: #333;
        font-size: 12px;
        line-height: 16px;
        white-space: nowrap;
        i {
          margin-left: 3px;
        }
      }
    }
  }
  &__price {
    .size(6);
    text-align: center;
    white-space: nowrap;
    i {
      margin-left: 3px;
    }
  }
}
  
</style>