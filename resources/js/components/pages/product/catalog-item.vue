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
            <button :disabled="disWish" 
              @click="addToWish(item.id)" 
              v-tooltip.top="toolWishStr" 
              class="button-ui button-ui_white button-ui_icon wishlist-btn" 
              :class="{
                'button-ui_done': isInWish, 
                'button-ui_action-icon-on': disWish && !isInWish, 
                'button-ui_action-icon-off': disWish && isInWish
              }">
              <i class="fa"
                :class="{
                  'fa-heart-o': !disWish,
                  'fa-check': disWish && !isInWish,
                  'slideDown': disWish,
                  'fa-trash-o': disWish && isInWish
                }"
              ></i>
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
          <avail-links :stock="item.stock"></avail-links>
        </div>
      </div>
    </div>
</template>

<script>
  import voblers from './voblers.vue';
  import availLinks from './avail-links.vue';
  import { mapGetters, mapActions } from 'vuex';
    export default {
        data() {
            return {
              inItem: false,
              disWish: false
            }
        },
        props: [
          'item'
        ],
        computed: {
          ...mapGetters([
           'auth',
           'wishlist'
          ]),
          isInWish() {
            return this.wishlist.items.indexOf(this.item.id) != -1;
          },
          toolWishStr() {
            return this.isInWish ? 'Удалить из избранного' : 'Добавить в избранное';
          },
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
          availLinks
        },
        methods: {
          ...mapActions([
            'addToLocalWishlist',
            'delFromLocalWishlist'
          ]),
          clickBuy() {
            this.$router.push('/product/'+this.item.chpu);
          },
          addToWish(id) {
            if (!this.auth) {
              this.disWish = true;
              setTimeout(() => {
                if (this.isInWish) {
                  this.delFromLocalWishlist(id);
                } else {
                  this.addToLocalWishlist(id);
                }
                this.disWish = false;
              } , 1000);
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
  .button-ui.button-ui_action-icon-on {
    border: 1px solid @main-color;
    color: @main-color;
    i {
      font-size: 18px;
    }
    &:hover {
      color: #fff;
    }
  }
  .button-ui.button-ui_action-icon-off {
      border: 1px solid #cc2e12 !important;
      color: #cc2e12;
    i {
      font-size: 18px;
    }
    &:hover {
      color: #fff;
    }
  }

  .slideDown{
    animation-name: slideDown;
    -webkit-animation-name: slideDown;  
 
    animation-duration: 1s; 
    -webkit-animation-duration: 1s;
 
    animation-timing-function: ease;    
    -webkit-animation-timing-function: ease;    
 
    visibility: visible !important;                     
}
 
@keyframes slideDown {
    0% {
        transform: translateY(-100%);
    }
    50%{
        transform: translateY(8%);
    }
    65%{
        transform: translateY(-4%);
    }
    80%{
        transform: translateY(4%);
    }
    95%{
        transform: translateY(-2%);
    }           
    100% {
        transform: translateY(0%);
    }       
}
 
@-webkit-keyframes slideDown {
    0% {
        -webkit-transform: translateY(-100%);
    }
    50%{
        -webkit-transform: translateY(8%);
    }
    65%{
        -webkit-transform: translateY(-4%);
    }
    80%{
        -webkit-transform: translateY(4%);
    }
    95%{
        -webkit-transform: translateY(-2%);
    }           
    100% {
        -webkit-transform: translateY(0%);
    }   
}
</style>