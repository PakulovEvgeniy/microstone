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
            <add-in-list-button
              :item="item"
              :list="wishlist.items"
              :authOnly="true"
              delLocalAction="delFromLocalWishlist"
              addLocalAction="addToLocalWishlist"
              addAuthAction="addToServerWishlist"
              delAuthAction="delFromServerWishlist"
              toolStrAdd="Добавить в избранное"
              toolStrDel="Удалить из избранного"
              icon="fa-heart-o"
            ></add-in-list-button>
            <add-in-list-button
              :item="item"
              :list="compare.items"
              :authOnly="false"
              delLocalAction="delFromLocalCompare"
              addLocalAction="addToLocalCompare"
              toolStrAdd="Добавить в сравнение"
              toolStrDel="Удалить из сравнения"
              icon="fa-bar-chart"
            ></add-in-list-button>

            <div class="primary-btn">
              <buy-button
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
           'cart'
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
          buyButton
        },
        methods: {
          ...mapActions([
            'addToLocalWishlist',
            'delFromLocalWishlist'
          ]),
          clickBuy() {
            this.$router.push('/product/'+this.item.chpu);
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

  .n-catalog-product__buttons button{
    margin-right: 12px;
  }
  .n-catalog-product__buttons button:first-child {
   margin: 0 12px 0 auto;
  }
  
</style>