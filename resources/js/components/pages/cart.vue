<template>
    <div v-show="mounted" class="account-cart" v-title="title">
      <h1>Корзина</h1>
      <info-block v-if="!auth && showCartInfo" :header="headerInfo" @close="onCloseInfo">
        <p>Вы не авторизованы, если вы являетесь зарегистрированным пользователем, то возможно у вас есть сохраненная корзина на нашем сервере. Для {{(this.cartQty == 0 ? 'восстановления или сохранения корзины на сервере' : 'объединения с этой корзиной')}} пожалуйста <router-link class="ui-link ui-link_blue ui-link_pseudolink" to="/login">авторизуйтесь!</router-link></p>
        <p>Для того, чтобы иметь доступ к корзине с различных устройств, пожалуйста <router-link class="ui-link ui-link_blue ui-link_pseudolink" to="/register">зарегистрируйтесь!</router-link></p>
      </info-block>
      <cart-empty v-if="cartQty==0"></cart-empty>
      <cart-goods v-else></cart-goods>
    </div>
</template>

<script>
    import { mapGetters } from 'vuex';
    import CartEmpty from './cart/cart-empty.vue';
    import CartGoods from './cart/cart-goods.vue';
    import InfoBlock from '../system/info-block.vue';
    export default {
        data() {
            return {
              headerInfo: 'Внимание!',
            }
        },
        computed: {
          ...mapGetters([
            'cart',
            'cartQty',
            'mounted',
            'showCartInfo',
            'auth'
          ]),
          title() {
            return 'Моя корзина';
          }
        },
        methods: {
          onCloseInfo() {
            this.$store.commit('setShowCartInfo', false);
          }
        },
        components: {
          CartEmpty,
          CartGoods,
          InfoBlock
        },
        beforeRouteEnter (to, from, next) {
          next(vm => {
            vm.$store.commit('setNonVisibleAside', true);
          })
        }
    }
</script>

<style lang="less">
  .account-cart {
    h1 {
      font-size: 24px;
      font-weight: bold;
      margin: 10px 0;
    }
  }
</style>