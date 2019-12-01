<template>
  <button
    :disabled="disable"
    @click="addToCart(item.id)"
    v-tooltip.top="toolStr"
    class="buy button-ui button-ui_brand"
    :class="{'active': isInList, 'button-ui_passive': passive}"
  >
  {{btnText}}
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
    'passive'
  ],
  methods: {
    addToCart(id) {
      if (!this.auth) {
        if (this.isInList) {
            this.$router.push('/account/cart');
        } else {
          this.$store.dispatch('addToLocalCart', id);
        }
      }
    }
  }
};
</script>

<style lang="less">
@import '../../../less/vars.less';
  button.buy {
    width: 100px;
    &.active {
      color: @main-color;
      border: 1px solid @main-color;
      background-image: none;
      background-color: #fff;
      &:hover {
        box-shadow: none;
      }
    }
  }
</style>