<template>
    <div class="wishlist-products" :class="{selectable: select}">
      <div @click="$emit('cancelSelection')" class="mobile-selection-header">
        <i class="fa fa-arrow-left"></i>
        Выберите товар
        <span class="products-counter">{{qtyChecked ? qtyChecked : ''}}</span>
      </div>
      <div 
        class="wishlist-product" 
        v-for="(el, ind) in wishProducts"
        :class="{'last': ind == wishProducts.length-1}"
        :key="el.id">
        <div class="m-checkbox">
          <input :id="'wlp-'+el.id" type="checkbox" :value="el.id" v-model="checkProds">
          <label :for="'wlp-'+el.id"><i class="fa fa-check"></i></label>
        </div>
        <div class="block-1">
          <div class="image">
            <router-link :to="'/product/'+el.chpu"><img :src="el.image"></router-link>
          </div>
          <div class="wishlist-product__caption">
            <div class="name"><router-link :to="'/product/'+el.chpu">{{el.name}}</router-link></div>
            <div class="product-info__voblers">
              <voblers></voblers>
            </div>
            <div class="wishlist-product__avail">
              <avail-links :stock="el.stock" :icon="true"></avail-links>
            </div>
          </div>
        </div>
        <div class="block-2">
          <div class="wishlist-product__price">
            <div class="price">
              <div v-show="+el.percent" class="previous">
                <span class="total">{{'от ' + Math.round(el.price*100)/100}}</span>
                <i class="fa fa-rub"></i>
                <span class="percent">{{'- '+el.percent+'%'}}</span>
              </div>
              <div class="price_g">
                <span>{{+el.percent ? ('от '+Math.round((+el.price-(+el.percent/100*el.price))*100)/100) : 'от ' +Math.round(el.price*100)/100}}</span>
                <i class="fa fa-rub"></i>
              </div>
            </div>
          </div>
          <div class="wishlist-product__buttons">
            <buy-button
              v-tooltip.top="cart.items.indexOf(el.id) != -1 ? 'Перейти в корзину'  : 'Добавить в корзину'"
              :item="el"
              :list="cart.items"
              :small="true"
            ></buy-button>
            <span class="but-group">
            <add-in-list-button
              v-tooltip.top="compare.items.indexOf(el.id) != -1 ? 'Удалить из сравнения' : 'Добавить в сравнение'"
              :item="el"
              :list="compare.items"
              :authOnly="false"
              delLocalAction="delFromLocalCompare"
              addLocalAction="addToLocalCompare"
              icon="fa-bar-chart"
            ></add-in-list-button>
            <button v-if="auth"
              @click="$emit('addToOtherList', [el.id])"
              v-tooltip.top="'Добавить товар в другой список'"
              class="button-ui button-ui_white button-ui_icon"
            ><i class="fa fa-plus"></i></button>
            </span>
          </div>
        </div>
        <a @click="delFromWish(el.id)" class="wishlist-product__remove"><span>Удалить</span></a>
      </div>
    </div>
</template>

<script>
  import { mapGetters, mapActions } from 'vuex';
  import voblers from '../../product/voblers.vue';
  import availLinks from '../../product/avail-links.vue';
  import addInListButton from '../../../system/add-in-list-button.vue';
  import buyButton from '../../../system/buy-button.vue';
    export default {
        data() {
            return {
            }
        }, 
        props: [
          'wishProducts',
          'curGroup',
          'select',
          'prodChecked',
          'qtyChecked'
        ],
        computed: {
          ...mapGetters([
           'auth',
           'compare',
           'cart'
          ]),
          checkProds: {
            get () { return this.prodChecked },
            set (value) { this.$emit('updateCheckProds', value) }
          }
        },
        components: {
          voblers,
          availLinks,
          addInListButton,
          buyButton
        },
        methods: {
          ...mapActions([
            'delFromLocalWishlist',
            'delFromServerWishlist'
          ]),
          delFromWish(id) {
            if (!this.auth) {
              this.delFromLocalWishlist(id);
            } else {
              this.delFromServerWishlist({id: id, group_id: this.curGroup});
            }
          }
        }
    }
</script>

<style lang="less">
  .wishlist-products {
    background-color: #fff;
    .mobile-selection-header {
      display: none;
    }
    &.selectable {
      .wishlist-product {
        overflow: hidden;
        padding-left: 55px;
        &__buttons {
          transition: all .3s ease 0s;
          opacity: 0;
          margin-right: -190px;
        }
        &__remove {
          transition: opacity .3s ease 0s;
          opacity: 0;
        }
        .m-checkbox {
          display: block;
          position: absolute;
          top: 0;
          left: 0;
          width: 100%;
          height: 100%;
          opacity: 1;
          margin: 0;
          cursor: pointer;
          z-index: 10;
          label {
            position: absolute;
            top: 0;
            left: 0;
            display: block;
            height: 100%;
            width: 100%;
            padding: 0;
            &::before {
              top: calc(50% - 10px);
              left: 20px;
            }
            i {
              top: calc(50% - 25px);
              left: 24px;
            }
            &:hover {
              box-shadow: inset 0 0 0 1px #a3a3a3;
            }
          }
          input[type="checkbox"]:checked + label {
            box-shadow: none;
          }
        }
      }  
    }
    .wishlist-product {
      transition: padding-left .3s ease 0s;
      border-bottom: 1px solid #ddd;
      padding: 30px 20px;
      position: relative;
      display: flex;
      flex-wrap: wrap;
      align-items: center;
      white-space: nowrap;
      &.last {
        border-bottom-left-radius: 8px;
        border-bottom-right-radius: 8px;
        border-bottom: none;
      }
      .block-1 {
        display: flex;
        flex-grow: 200;
      }
      .block-2 {
        display: flex;
        flex-grow: 1;
        justify-content: flex-end;
        margin-top: 10px;
      }
      .image {
        img, a {
          display: block;
        }
      }
      &__caption {
        flex-grow: 1;
        margin-left: 13px;
        white-space: normal;
        .name {
          padding-bottom: 10px;
          a {
            color: #333;
          }
        }
      }
      &__price {
        transition: width .3s ease 0s;
        text-align: right;
        .price {
          font-size: 24px;
        }
        .previous {
          font-style: normal;
          font-size: 13px;
          .total {
            text-decoration: line-through;
          }
          i {
            margin-left: 5px;
            color: #b2b2b2;
          }
          .percent {
            font-size: 12px;
            color: #fff;
            padding: 1px 5px 1px 2px;
            background-color: rgba(29, 113, 184,0.9);
            position: relative;
            margin-left: 8px;
            &:before {
              content: '';
              border: 8px solid transparent;
              border-right-color: rgba(29, 113, 184,0.9);
              position: absolute;
              left: -16px;
              top: 0;
            }
          }
        }
        .price_g {
          font-size: 20px;
          color: #333;
          font-weight: bold;
          i {
            margin-left: 5px;
            color: #b2b2b2;
            font-weight: normal;
          }
        }
      }
      &__buttons {
        margin-left: 13px;
        transition: all .5s ease 0s;
        opacity: 1;
        margin-right: 0;
      }
      &__remove {
        transition: opacity .6s ease 0s;
        border-bottom: 1px dotted gray;
        color: gray;
        display: block;
        font-size: 12px;
        opacity: 1;
        position: absolute;
        right: 20px;
        top: 10px;
        &:hover {
          text-decoration: none;
        }
      }
      .m-checkbox {
        transition: opacity .3s ease .3s;
        display: none;
        opacity: 0;
      }
    }
  }

  @media (max-width: 1199px) and (min-width: 992px) {
    .wishlist-product {
      &__buttons {
        .buy {
          display: block;
          margin-bottom: 5px;
        }
        .but-group {
          display: flex;
          justify-content: space-between;
        }
      }
    }
    .wishlist-products.selectable {
      .wishlist-product {
        &__buttons {
          margin-right: -105px;
        }
      }
    }
  }

  @media (max-width: 991px) {
    .wishlist-products.selectable {
      .wishlist-product {
        &__buttons {
          margin-right: -145px;
        }
      }
    }
  }

  @media (max-width: 767px) {
    .wishlist-products.selectable {
      position: fixed;
      background: #fff;
      top: 0;
      width: 100%;
      bottom: 65px;
      z-index: 1010;
      padding-top: 60px;
      overflow-y: auto;
      margin-left: -10px;
      margin-right: -10px;
      .mobile-selection-header {
        cursor: pointer;
        background: #424a54;
        color: #fff;
        display: block;
        position: fixed;
        top: 0;
        height: 60px;
        line-height: 60px;
        text-align: center;
        font-weight: bold;
        width: 100%;
        padding: 0 20px;
        z-index: 100;
        i {
          float: left;
          line-height: 60px;
        }
        span {
          float: right;
        }
      }
      .wishlist-product {
        padding: 15px 15px 15px 45px;
        &.last {
          border-bottom: 1px solid #ddd;
          border-radius: 0;
        }
        .block-2 {
          display: none;
        }
        .block-1 {
          align-items: center;
        }
        &__caption {
          .product-info__voblers, .wishlist-product__avail {
            display: none;
          }
          .name {
            padding-bottom: 0;
          }
        }
        .m-checkbox label i {
          left: 23px;
        }
        .m-checkbox label {
          &:hover {
            box-shadow: none;
          }
        }
      }
    }
  }
</style>