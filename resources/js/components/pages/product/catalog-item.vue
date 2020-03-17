<template>
    <div class="catalog-item" @mouseenter="inItem=true" @mouseleave="inItem=false">
      <div class="n-catalog-product">
        <div class="n-catalog-product__main">
          <div class="n-catalog-product__info">
            <div class="product-info">
              <div class="product-info__image">
                <router-link :to="'/product/'+item.chpu">
                  <picture>
                    <img :src="item.image">
                  </picture>
                </router-link>
                <div class="product-info__code">
                  <span>Код:</span>
                  <span>{{item.sku}}</span>
                </div>
              </div>
              <div class="product-info__title">
                <div class="product-info__title-link">
                  <router-link :to="'/product/'+item.chpu" class="ui-link">{{item.name}}</router-link>
                </div>
                <span class="product-info__title-description">
                  {{item.small_desc}}
                </span>
                <div class="product-info__voblers">
                  <voblers></voblers>
                </div>
              </div>
              <div class="product-info__social">
                <div class="product-info__stat">
                  <product-rating></product-rating>
                  <a class="product-info__comments-count ui-link ui-link_blue">
                    <i class="fa fa-comment-o"></i>
                    &nbsp;1
                  </a>
                </div>
              </div>
            </div>
          </div>
          <div class="n-catalog-product__price">
            <div class="product-price">
              <div class="product-price__current">
                {{price}}
                <i class="product-price__rub_icon fa fa-rub"></i>
              </div>
              <div v-if="item.sold" class="product-price__sold">Продажи: <span>{{item.sold}} шт.</span></div>
            </div>
          </div>
          <div class="n-catalog-product__buttons">
            <add-in-list-button
              v-tooltip.top="wishlist.items.indexOf(item.id) != -1 ? 'Удалить из избранного' : 'Добавить в избранное'"
              :item="item"
              :list="wishlist.items"
              :authOnly="true"
              delLocalAction="delFromLocalWishlist"
              addLocalAction="addToLocalWishlist"
              addAuthAction="addToServerWishlist"
              delAuthAction="delFromServerWishlist"
              icon="fa-heart-o"
            ></add-in-list-button>
            <div class="compare-button" :class="{incompare: compare.items.indexOf(item.id) != -1}">
            <add-in-list-button
              v-tooltip.top="compare.items.indexOf(item.id) != -1 ? 'Удалить из сравнения' : 'Добавить в сравнение'"
              :item="item"
              :list="compare.items"
              :authOnly="false"
              delLocalAction="delFromLocalCompare"
              addLocalAction="addToLocalCompare"
              icon="fa-bar-chart"
              @click="clickCompare"
            ></add-in-list-button>
            <compare-popover v-if="compare.items.indexOf(item.id) != -1"></compare-popover>
            </div>
            <div class="primary-btn">
              <buy-button
                v-tooltip.top="cart.items.indexOf(item.id) != -1 ? 'Перейти в корзину'  : 'Добавить в корзину'"
                :item="item"
                :list="cart.items"
                :passive="inItem ? false : true"
              ></buy-button>
            </div>
          </div>
        </div>
        <div class="n-catalog-product__footer">
          <avail-links :stock="item.stock"></avail-links>
        </div>
      </div>
    </div>
</template>

<script>
  import voblers from './voblers.vue';
  import availLinks from './avail-links.vue';
  import addInListButton from '../../system/add-in-list-button.vue';
  import buyButton from '../../system/buy-button.vue';
  import productRating from './product-rating.vue';
  import comparePopover from '../compare/compare-popover.vue';
  import { mapGetters, mapActions } from 'vuex';
    export default {
        data() {
            return {
              inItem: false,
              disWish: false,
              disCompare: false
            }
        },
        props: [
          'item'
        ],
        computed: {
          ...mapGetters([
           'auth',
           'wishlist',
           'compare',
           'cart',
           'getScreenState'
          ]),
          price() {
            if (this.item.min_price && this.item.max_price) {
              let pr1 = parseFloat(this.item.min_price);
              let pr2 = parseFloat(this.item.max_price);
              if (pr1 != pr2) {
                return pr1.toFixed(2) + ' - ' + pr2.toFixed(2);
              }
              return pr1.toFixed(2);
            } else {
              return '0';
            }
          }
        },
        components: {
          voblers,
          availLinks,
          addInListButton,
          buyButton,
          productRating,
          comparePopover
        },
        methods: {
          ...mapActions([
            'addToLocalWishlist',
            'delFromLocalWishlist'
          ]),
          clickBuy() {
            this.$router.push('/product/'+this.item.chpu);
          },
          goodsEnd(qty) {
            let c = qty % 100;
            let a1 = [1, 21, 31, 41, 51, 61, 71, 81, 91];
            let a2 = [2, 3, 4, 22, 23, 24, 32, 33, 34, 42, 43, 44, 52, 53, 54, 62, 63, 64, 72, 73, 74, 82, 83, 84, 92, 93, 94];
            if (a1.indexOf(c) != -1) {
              return 'товар';
            } else if(a2.indexOf(c) != -1) {
              return 'товара';
            } else {
              return 'товаров';
            }
          },
          clickCompare() {
            if (this.getScreenState == 1) {
              $notify("compare");
              $notify("compare", 'В сравнении ' + this.compare.items.length + ' ' + this.goodsEnd(this.compare.items.length), "compare");
            }
          }
        }
    }
</script>
<style lang="less">
@import '../../../../less/vars.less';
  .product-price__current {
    font-size: 20px;
    font-weight: bold;
  }
  .product-price__current i {
    color: #afafaf;
    font-size: 18px;
  }
  
  .product-info__rating i {
    color: #feb909;
    font-size: 12px;
  }

  .product-price__sold span {
    font-weight: bold;
    color: rgb(29, 113, 184);
  }

  .n-catalog-product__buttons {
    .compare-button {
      position: relative;
      .compare-info {
        display: none;
      }
    }
    .compare-button.incompare:hover {
      .compare-info {
        display: block;
        z-index: 1000;
      }
    }
  }

  .n-catalog-product__buttons button{
    margin-right: 12px;
  }
  .n-catalog-product__buttons button:first-child {
   margin: 0 12px 0 auto;
  }

  .vs-notify.compare .ntf {
    border-radius: 8px;
    background-color: #333;
    bottom: 75px;
    box-shadow: 0 4px 8px rgba(0,0,0,0.16);
    color: #fff;
    max-width: 100%;
    min-width: 315px;
    left: 50%;
    padding: 19px 16px;
    border-left: none;
    div {
      display: flex;
      justify-content: space-between;
      align-items: center;
      background-color: #333;
      color: #fff;
      a {
        color: #fc7b08;
        &:hover {
          text-decoration: none;
        }
      }
    }
  }  
</style>