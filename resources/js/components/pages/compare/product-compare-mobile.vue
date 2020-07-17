<template>
  <div class="compare__product-mobile">
    <div class="image">
      <a>
        <picture><img :alt="product.name" :src="product.image_2"></picture>
      </a>
      <div @click="clickGallery($event)" @mousedown="mouseDownGal($event)" class="zoom-button">
        <i class="fa fa-search-plus" aria-hidden="true"></i>
      </div>      
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
        v-tooltip.bottom="isInCartAll(product) ? 'Перейти в корзину'  : 'Добавить в корзину'"
        :item="product"
        :list="cart.items"
        :small="true"
      ></buy-button>
      <add-in-list-button
        v-tooltip.bottom="isInList ? 'Удалить из избранного' : 'Добавить в избранное'"
        :item="product"
        :list="wishlist.items"
        :authOnly="true"
        :isWish="true"
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
          clientX: 0,
          clientY: 0
        }
    },
    props: [
      'product'
    ],
    computed: {
      ...mapGetters([
        'auth',
        'wishlist',
        'cart',
        'isInCartAll',
        'isInWishAll'
      ]),
      isInList() {
        return this.isInWishAll(this.product);
      }
    },
    methods: {
      ...mapActions([
        'addToLocalWishlist',
        'delFromLocalWishlist',
        'addToServerWishlist',
        'delFromServerWishlist'
      ]),
      mouseDownGal(e) {
        this.clientX = e.clientX;
        this.clientY = e.clientY;
      },
      clickGallery(e) {
        let difX = e.clientX - this.clientX;
        if (difX<0) difX = -difX;
        let difY = e.clientY - this.clientY;
        if (difY<0) difY = -difY;
        if (difX<5 && difY<5) {
          this.$emit('onZoom', this.product);
        }
      },
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
      margin-right: 8px;
      position: relative;
      .zoom-button {
        border-radius: 20px;
        background: #fff;
        cursor: pointer;
        display: none;
        height: 40px;
        left: 20px;
        position: absolute;
        top: 20px;
        width: 40px;
        > i {
          position: absolute;
          left: 13px;
          top: 10px;
          color: #333;
        }
      }
      &:hover  .zoom-button {
        display: block;
      }
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