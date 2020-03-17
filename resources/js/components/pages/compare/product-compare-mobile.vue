<template>
  <div class="compare__product-mobile">
    <div class="image">
      <a>
        <picture><img :alt="product.name" :src="product.image_2"></picture>
      </a>      
    </div>
    <div class="compare__mobile-content">
      <div class="caption">
        <router-link :to="'/product/'+product.chpu">{{product.name}}</router-link>
      </div>
      <div class="price"><span>от {{product.price_with_discount}}</span><i class="fa fa-rub"></i></div>
      <product-rating></product-rating>
    </div>
    <div class="compare__product-controls">
      <buy-button
        v-tooltip.bottom="cart.items.indexOf(product.id) != -1 ? 'Перейти в корзину'  : 'Добавить в корзину'"
        :item="product"
        :list="cart.items"
        :small="true"
      ></buy-button>
      <add-in-list-button
        v-tooltip.bottom="isInList ? 'Удалить из избранного' : 'Добавить в избранное'"
        :item="product"
        :list="wishlist.items"
        :authOnly="true"
        delLocalAction="delFromLocalWishlist"
        addLocalAction="addToLocalWishlist"
        addAuthAction="addToServerWishlist"
        delAuthAction="delFromServerWishlist"
        icon="fa-heart-o"
      ></add-in-list-button>
      <trash-button @click="clickTrash"></trash-button>
    </div>
  </div>
</template>

<script>
  import { mapGetters, mapActions } from 'vuex';
  import productRating from '../product/product-rating.vue';
  import buyButton from '../../system/buy-button.vue';
  import addInListButton from '../../system/add-in-list-button.vue';
  import trashButton from '../../system/trash-button.vue';
  export default {
    data() {
        return {
        }
    },
    props: [
      'product'
    ],
    computed: {
      ...mapGetters([
        'auth',
        'wishlist',
        'cart'
      ]),
      isInList() {
        return this.wishlist.items.indexOf(this.product.id) != -1;
      }
    },
    methods: {
      ...mapActions([
        'addToLocalWishlist',
        'delFromLocalWishlist',
        'addToServerWishlist',
        'delFromServerWishlist'
      ]),
      clickFavorite() {
        let id = this.product.id;
        if (this.auth) {
          if (this.isInList) {
            this.delFromServerWishlist(id);
          } else {
            this.addToServerWishlist(id);
          }
        } else {
          if (this.isInList) {
            this.delFromLocalWishlist(id);
          } else {
            this.addToLocalWishlist(id);
          }
        }
      },
      clickTrash() {
        this.$store.dispatch('delFromLocalCompare', this.product.id);
      }
    },
    components: {
      productRating,
      buyButton,
      addInListButton,
      trashButton
    }
  }
</script>

<style lang="less">
@import '../../../../less/vars.less';
  .compare__product-mobile {
    display: flex;
    align-items: center;
    padding: 0 0 20px 10px;
    .image {
      width: 80px;
      height: 80px;
    }
  }
  .compare__mobile-content {
    display: flex;
    align-items: center;
    flex-grow: 1;
    flex-wrap: wrap;
    .caption {
      width: 60%;
      margin-right: 30px;
      a:hover {
        text-decoration: none;
        color: #3b3b3b;
      }
    }
    .price {
      color: #333;
      font-weight: bold;
      font-size: 22px;
      width: calc(40% - 30px);
      white-space: nowrap; 
      span {
        margin-right: 10px;
      }
      i {
        font-size: 20px;
        color: #b2b2b2;
      }
    }
  }

  .compare__product-controls {
    display: flex;
    margin-right: 15px;
    button {
      margin-right: 5px;
    }
  }

  @media (max-width: 767px) {
    .compare__mobile-content {
      flex-direction: column;
      align-items: unset;
      .caption {
        width: unset;
      }
      .price {
        font-size: 16px;
        span {
          margin-right: 7px;
        }
        i {
          font-size: 15px;
        }
      }
    }
  }
</style>