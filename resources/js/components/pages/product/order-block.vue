<template>
    <div class="order-block">
      <div class="order-block__main-offer">
        <div class="order-block__spin">
          <div class="order-block__price">{{priceViz}}<i class="fa fa-rub"></i></div>
           <qty-spinner v-model="ordQty"></qty-spinner>
        </div>
        <div class="order-block__other-offers">
          <div class="product-info__stat">
              <product-rating></product-rating>
          </div>
          <ul v-if="listDiscount.length" class="order-block__offer-items">
            <li v-for="el in listDiscount">{{el.text}}<i class="fa fa-rub"></i></li>
          </ul>
        </div>
        <div v-if="curCharact" class="order-block__variant">
          <div>Выберите характеристику товара</div>
          <dropdown
            :options="product.characts"
            :selected="product.characts[curCharactInd]"
            v-on:updateOption="methodToRunOnSelect"
          >
          </dropdown>
        </div>
      </div>
      <div class="order-block__controls">
        <div class="order-block__info-str">
          <span>{{infoStr}}<i class="fa fa-rub"></i></span>
          <special-buttons :curCharact="curCharact" :product="product"></special-buttons>
        </div>
        <div class="cart-btn">
          <button @click="addToCart" class="button-ui button-ui_brand">В корзину</button>
          <span v-if="isInList">товар <router-link to="/account/cart">в корзине</router-link></span>
        </div>
      </div>
    </div>
</template>

<script>
  import qtySpinner from './qty-spinner.vue';
  import productRating from './product-rating.vue';
  import dropdown from '../../system/dropdown.vue';
  import specialButtons from './special-buttons.vue';
  import { mapGetters } from 'vuex';
    export default {
        data() {
            return {
              ordQty: 1,
              curCharactInd: 0
            }
        },
        props: [
        ],
        computed: {
          ...mapGetters([
            'product',
            'cart',
            'wishlist',
            'compare'
          ]),
          curCharact() {
            if (!this.product || !this.product.have_charact) return;
            if (this.curCharactInd>=this.product.characts.length || this.curCharactInd<0) {
              this.curCharactInd = 0;
            }
            return this.product.characts[this.curCharactInd]
          },
          price() {
            if (this.product) {
              if (this.product.have_charact && this.curCharact) {

                  let curP = this.product.price.find((el) => {
                    return el.id == this.curCharact.id;
                  });
                  if (curP) {
                    return (+curP.price).toFixed(2);
                  }

              } else {
                return (+this.product.price.min).toFixed(2);
              }
            }
            return 0;
          },

          listDiscount() {
            if (!this.product) return [];
            let dg = this.product.discount_group;
            if (!dg.length) return [];
            return dg.map((el) => {
              let pr = (Math.ceil(this.price*(1-el.discount/100)*10)/10).toFixed(2);
              return {text:'от '+el.qty+' шт. - '+pr, price: pr, qty: el.qty};
            }).sort((el1, el2) => {
              return el1.qty - el2.qty;
            });
          },
          priceViz() {
            for (let i = this.listDiscount.length-1; i >= 0; i--) {
              if (this.ordQty>=this.listDiscount[i].qty) {
                return this.listDiscount[i].price;
              }
            }
            return this.price;
          },
          infoStr() {
            let sum = this.ordQty * this.priceViz;
            return 'Добавить в корзину '+this.ordQty + ' шт. на сумму ' + sum.toFixed(2);
          },
          isInList() {
            return !!this.cart.items.find((el) => {
              if (this.product.have_charact) {
                return el.id == this.product.id && el.characteristic == this.curCharact.id;
              } else {
                return el.id == this.product.id;
              }
            });
          }
          
        },
        methods: {
          methodToRunOnSelect(payload) {
            for (var i = 0; i < this.product.characts.length; i++) {
              if (this.product.characts[i].id == payload.id) {
                this.curCharactInd = i;
                return;
              }
            }
            this.curCharactInd = 0;
          },
          addToCart() {
            this.$store.dispatch('addToCart', [{id: this.product.id, characteristic: this.product.have_charact ? this.curCharact.id : '', qty: this.ordQty}]);
          }
        },
        components : {
          qtySpinner,
          productRating,
          dropdown,
          specialButtons
        } 
    }
</script>

<style lang="less">
@import '../../../../less/smart-grid.less';
  .order-block {
    margin-left: 410px;
    .md(margin-left, 0);
    &__main-offer {
      background-color: lighten(#fdf2c0, 5%);
      border-radius: 6px 6px 0 0;
      box-shadow: 0 5px 5px -2px rgba(4,10,20,.4);
      padding: 15px;
      margin-top: 30px;
    }
    &__spin {
      display: flex;
      flex-wrap: nowrap;
      justify-content: space-between;
      width: 100%;
    }
    &__price {
      white-space: nowrap;
      font-size: 26px;
      font-weight: bold;
      min-width: 90px;
      i {
        margin-left: 10px;
        font-size: 24px;
      }
    }
    &__other-offers {
      display: flex;
      flex-wrap: nowrap;
      justify-content: space-between;
      width: 100%;
    }
    &__offer-items {
      text-align: right;
      li {
        color: #333;
        font-size: 12px;
        line-height: 16px;
        white-space: nowrap;
        i {
          font-size: 11px;
          margin-left: 5px;
        }
      }
    }
    &__info-str {
      flex-grow: 1;
      text-align: right;
      padding-top: 10px;
      font-size: 15px;
      > span i {
        margin-left: 3px;
        font-size: 14px;
      }
    }
    &__controls {
      background-color: #ececec;
      box-shadow: 0 2px 2px 0 #cad8e1;
      border-radius: 0 0 6px 6px;
      border-top: none;
      padding: 15px;
      display: flex;
      flex-wrap: nowrap;
      justify-content: space-between;
      .cart-btn {
        padding-left: 30px;
        > * {
          display: block;
        }

        button {
          padding-right: 15px;
          padding-left: 15px;
          width: 100%;
          min-width: 120px;
        }
        span {
          color: #333;
          margin: 4px 0 0;
          white-space: nowrap;
          font-size: 14px;
          text-align: center;
          a {
            color: #005cbf;
            border-color: #b2d1f0;
            border-color: rgba(0,102,204,.3);
            cursor: pointer;
            border-bottom: 1px solid;
            &:hover {
              color: #c90000;
            }
          }
        }
      } 
    }
  }
</style>