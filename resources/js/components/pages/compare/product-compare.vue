<template>
  <div class="compare__product">
    <i 
      @click="$emit('clickFixed')" 
      class="fa icon icon-lock"
      :class="{'fa-unlock-alt': !lock, 'fa-lock': lock, 'lock': lock}"
    ></i>
    <button 
      class="icon icon-favorite"
      :class="{'lock': lock, 'inwish': isInList}"
      v-tooltip.bottom="isInList ? 'Убрать из избранного' : 'Добавить в избранное'"
      @click="clickFavorite"
    ><i class="fa fa-heart-o"></i></button>
    <prodwish-popover @close="onClose" v-if="pcPopVis"  :list="product.characts" :elem="$el" :product="product"></prodwish-popover>
    <i 
      @click="$emit('clickTrash')" 
      class="fa fa-trash icon icon-trash"
      :class="{'lock': lock}"
    ></i>
    <div class="compare__product-image">
      <a>
        <picture><img :alt="product.name" :src="product.image_2"></picture>
      </a>
      <div @click="clickGallery($event)" @mousedown="mouseDownGal($event)" class="zoom-button">
        <i class="fa fa-search-plus" aria-hidden="true"></i>
      </div>      
    </div>
    <div class="compare__product-content">
      <div class="caption">
        <div class="item-name"><router-link event="" @click.native="click" :to="'/product/'+product.chpu">{{product.name}}</router-link></div>
        <div class="item-code">Код товара: <span>{{product.sku}}</span></div>
      </div>
      <div class="controls">
        <div class="item-price">
          <product-rating></product-rating>
          <div class="price"><span>от {{product.price_with_discount}}</span><i class="fa fa-rub"></i></div>
        </div>
        <div class="buttons" :class="{'lock': lock}">
          <buy-button
            v-tooltip.bottom="isInCartAll(product) ? 'Перейти в корзину'  : 'Добавить в корзину'"
            :item="product"
            :list="cart.items"
            :big="true"
          ></buy-button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  import { mapGetters, mapActions } from 'vuex';
  import productRating from '../product/product-rating.vue';
  import buyButton from '../../system/buy-button.vue';
  import prodwishPopover from '../product/prodwish-popover.vue';
  export default {
    data() {
        return {
          clientX: 0,
          clientY: 0,
          pcPopVis: false
        }
    },
    props: [
      'product',
      'lock'
    ],
    inject: {
      carousel: { default: {} }
    },
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
      
      onClose() {
        this.pcPopVis = false;
      },
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
        if (this.pcPopVis) {
          this.pcPopVis = false;
          return;
        }
        if (this.product.have_charact) {
          this.pcPopVis = true;
        } else {
          if (this.isInList) {
            this.$store.dispatch('delFromWishList', {id: this.product.id, characteristic: '', group_id: 0});
          } else {
            this.$store.dispatch('addToWishList', {id: this.product.id, characteristic: ''});
          }
        }
      },
      click() {
        if(!this.carousel.draggingStart) {
          this.$router.push('/product/'+this.product.chpu);
        }
      }
    },
    components: {
      productRating,
      buyButton,
      prodwishPopover
    }
  }
</script>

<style lang="less">
@import '../../../../less/vars.less';
  .compare__product {
    .prodcart-popover__cont {
      top: auto;
      left: auto;
      bottom: 70px;
      right: -90px;
    }
    .prodcart-popover__scroll, .prodwish-popover__scroll {
      max-height: 190px;
      display: none;
    }
    padding: 10px 5px;
    position: relative;
    &-image {
      height: 80px;
      margin: auto;
      width: 80px;
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
    .icon {
      color: #afafaf;
      font-size: 20px;
      position: absolute;
      display: none;
      cursor: pointer;
      &.icon-lock {
        left: 10px;
        &.lock {
          color: #000;
          &:hover {
            color: #afafaf !important;
          }
        }
      }
      &.icon-favorite {
        background-color: transparent;
        box-shadow: none;
        border: none;
        right: 10px;
        padding: 0;
        outline: none;
        &.inwish {
          color: @main-color;
          &:hover {
            color: @main-color;
          }
        }
      }
      &.icon-trash {
        right: 11px;
        top: 36px;
        font-size: 22px;
      }
      &.lock {
        display: block;
        &.icon-trash {
          display: none;
        }
      }
    }
    &:hover {
      .icon {
        display: block;
        &:hover {
          color: #000;
        }
      }
      .controls {
        .buttons {
          display: block;
        }
      }
      .prodwish-popover__scroll {
        display: block;
      }
    }
    &-content {
      .caption {
        color: #333;
        padding: 0;
        min-height: 60px;
        .item-name {
          height: 48px;
          font-weight: normal;
          max-height: 48px;
          margin: 10px 0;
          overflow: hidden;
          a {
            color: #555;
            text-decoration: none;
            &:hover {
              color: @main-color;
            }
          }
        }
        .item-code {
          color: #888;
          font-size: .8em;
          margin-top: 2px;
        }
      }
      .product-info__rating {
        i {
          font-size: 15px;
        }
      }
      .controls {
        .price {
          font-size: 1em;
          color: #333;
          font-weight: bold;
          margin-top: 10px;
          i {
            margin-left: 5px;
            color: #b2b2b2;
            font-size: 15px;
          }
        }
        .buttons {
          display: none;
          bottom: 10px;
          position: absolute;
          right: 5px;
          &.lock {
            display: block;
          }
        }
      }
    }
  }

  @media (min-width: 1200px){
    .compare__product {
      &-content {
        .caption {
          .item-name {
            height: 60px;
            max-height: 60px;
          }
        }
      }
    }
  }
   @media (max-width: 991px){
    .compare__product {
      .icon {
        display: block;
      }
      &-content {
        .controls {
          .buttons {
            display: block;
          }
        }
      }
    }
  }
</style>