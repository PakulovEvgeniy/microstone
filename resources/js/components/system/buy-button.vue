<template>
<div class="buy-button">
  <button 
    :disabled="disable"
    @click="addToCart(item)"
    class="buy button-ui button-ui_brand"
    :class="{'active': isInCartAll(item), 'button-ui_passive': passive, small: small, big: big}"
  >
  <span>{{btnText}}</span>
  <i class="fa" :class="{'fa-shopping-cart': !isInCartAll(item), 'fa-check': isInCartAll(item)}"></i>
  
  </button>
  <prodcart-popover @close="onClose" v-if="pcPopVis"  :list="item.characts" :elem="$el" :product="item"></prodcart-popover>
</div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex';
import prodcartPopover from '../pages/product/productcart-popover.vue';
export default {
  data() {
    return {
      disable: false,
      pcPopVis: false
    };
  },
  components: {
    prodcartPopover
  },
  computed: {
    ...mapGetters([
      'auth',
      'isInCartAll'
    ]),
    btnText() {
      return this.isInCartAll(this.item) ? 'В корзине'  : 'Купить';
    }
  },
  props: [
    'item',
    'list',
    'passive',
    'small',
    'big'
  ],
  methods: {
    onClose() {
      this.pcPopVis = false;
    },
    addToCart(it) {
      if (this.pcPopVis) {
        this.pcPopVis = false;
        return;
      }
      if (this.isInCartAll(it)) {
        this.$router.push('/account/cart');
      } else {
        if (it.have_charact) {
          if (it.characteristic) {
            this.$store.dispatch('addToCart', [{id: it.id, characteristic: it.characteristic, qty: 1}]);
          } else {
            this.pcPopVis = true;
          }
        } else {
          this.$store.dispatch('addToCart', [{id: it.id, characteristic: '', qty: 1}]);
        }
      }
    }
  }
};
</script>

<style lang="less">
@import '../../../less/vars.less';
  .buy-button {
    display: inline-block;
  }
  button.buy {
    width: 90px;
    &.active {
      color: @main-color;
      border: 1px solid @main-color;
      background-image: none;
      background-color: #fff;
      &:hover {
        box-shadow: none;
      }
    }
    &.small, &.big {
      i {
        display: none;
      }
    }
  }

  @media (max-width: 992px) {
    button.buy.small {
      width: 40px;
      i {
        display: inline-block;
      }
      > span {
        display: none;
      }
      ~ .prodcart-popover .prodcart-popover__cont {
        right: -40px;
        left: auto;
      }
    }
  }
</style>