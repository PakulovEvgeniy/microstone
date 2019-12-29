<template>
  <button
    :disabled="disable"
    @click="addToCart(item.id)"
    v-tooltip.top="toolStr"
    class="buy button-ui button-ui_brand"
    :class="{'active': isInList, 'button-ui_passive': passive, small: small}"
  >
  <span>{{btnText}}</span>
  <i class="fa" :class="{'fa-shopping-cart': !isInList, 'fa-check': isInList}"></i>
  </button>
</template>

<script>
import { mapGetters, mapActions } from 'vuex';
export default {
  data() {
    return {
      disable: false
    };
  },
  computed: {
    ...mapGetters([
      'auth'
    ]),
    isInList() {
      return this.list.indexOf(this.item.id) != -1;
    },
    toolStr() {
      return this.isInList ? 'Перейти в корзину'  : 'Добавить в корзину';
    },
    btnText() {
      return this.isInList ? 'В корзине'  : 'Купить';
    }
  },
  props: [
    'item',
    'list',
    'passive',
    'small'
  ],
  methods: {
    addToCart(id) {
      if (!this.auth) {
        if (this.isInList) {
            this.$router.push('/account/cart');
        } else {
          this.$store.dispatch('addToLocalCart', id);
        }
      } else {
        if (this.isInList) {
            this.$router.push('/account/cart');
        } else {
          this.$store.dispatch('addToCart', [id]);
        }
      }
    }
  }
};
</script>

<style lang="less">
@import '../../../less/vars.less';
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
    &.small {
      i {
        display: none;
      }
    }
  }

  @media (max-width: 991px) {
    button.buy.small {
      width: 40px;
      i {
        display: inline-block;
      }
      span {
        display: none;
      }
    }
  }
</style>