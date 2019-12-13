<template>
    <div class="wishlist-products">
      <div 
        class="wishlist-product" 
        v-for="(el, ind) in wishProducts"
        :class="{'last': ind == wishProducts.length-1}"
        :key="el.id">
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
            :item="el"
            :list="cart.items"
          ></buy-button>
          <add-in-list-button
            :item="el"
            :list="compare.items"
            :authOnly="false"
            delLocalAction="delFromLocalCompare"
            addLocalAction="addToLocalCompare"
            toolStrAdd="Добавить в сравнение"
            toolStrDel="Удалить из сравнения"
            icon="fa-bar-chart"
          ></add-in-list-button>
          <button v-if="auth"
            @click="addToOtherList(el.id)"
            v-tooltip.top="'Добавить товар в другой список'"
            class="button-ui button-ui_white button-ui_icon"
          ><i class="fa fa-plus"></i></button>
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
          'wishProducts'
        ],
        computed: {
          ...mapGetters([
           'auth',
           'compare',
           'cart'
          ])
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
              this.delFromServerWishlist(id);
            }
          }
        }
    }
</script>

<style lang="less">
  .wishlist-products {
    .wishlist-product {
      transition: padding-left .3s ease 0s;
      border-bottom: 1px solid #ddd;
      padding: 30px 20px;
      position: relative;
      display: flex;
      align-items: center;
      white-space: nowrap;
      &.last {
        border-bottom-left-radius: 8px;
        border-bottom-right-radius: 8px;
        border-bottom: none;
      }
      .image {
        img, a {
          display: block;
        }
      }
      &__caption {
        flex-grow: 1;
        margin-left: 13px;
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
    }
  }
</style>