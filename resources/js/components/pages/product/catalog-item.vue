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
                  <div class="product-info__rating">
                    <i class="fa fa-star"></i>
                    <i class="fa fa-star"></i>
                    <i class="fa fa-star-o"></i>
                    <i class="fa fa-star-o"></i>
                    <i class="fa fa-star-o"></i>
                  </div>
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
            <button @click="addToWish(item.id)" v-tooltip.top="'Добавить в избранное'" class="button-ui button-ui_white button-ui_icon wishlist-btn">
              <i class="fa fa-heart-o"></i>
            </button>
            <button v-tooltip.top="'Добавить в лист ожидания'" class="button-ui button-ui_white button-ui_icon waitlist-btn">
              <i class="fa fa-clock-o"></i>
            </button>
            <div class="primary-btn">
              <button @click="clickBuy" class="button-ui button-ui_brand" :class="{'button-ui_passive' : !inItem}">Купить</button>
            </div>
          </div>
        </div>
        <div class="n-catalog-product__footer">
          <div class="n-catalog-product__avails">
            <div class="order-avail-wrap">
              <span class="avail-text">
                <span class="available">Доступно:</span>
                <a v-show="item.stock>0" class="avail-text__link ui-link ui-link_blue ui-link_pseudolink">В наличии</a>
                <a class="avail-text__link ui-link ui-link_blue ui-link_pseudolink">Под заказ</a>
              </span>
            </div>
          </div>
        </div>
      </div>
    </div>
</template>

<script>
  import voblers from './voblers.vue';
  import { mapGetters, mapActions } from 'vuex';
    export default {
        data() {
            return {
              inItem: false
            }
        },
        props: [
          'item'
        ],
        computed: {
          ...mapGetters([
           'auth'
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
          voblers
        },
        methods: {
          ...mapActions([
            'addToLocalWishlist'
          ]),
          clickBuy() {
            this.$router.push('/product/'+this.item.chpu);
          },
          addToWish(id) {
            if (!this.auth) {
              this.addToLocalWishlist(id);
            }
          }
        }
    }
</script>

<style>
  .product-price__current {
    font-size: 20px;
    font-weight: bold;
  }
  .product-price__current i {
    color: #afafaf;
    font-size: 18px;
  }
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
  .product-info__rating i {
    color: #feb909;
    font-size: 12px;
  }

  .product-price__sold span {
    font-weight: bold;
    color: rgb(29, 113, 184);
  }
</style>