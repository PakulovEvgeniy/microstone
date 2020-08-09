<template>
    <div class="cart-empty">
      <p><strong>Сейчас в корзине нет товаров</strong></p>
      <p>Посмотрите предложения на <router-link to="/">главной странице</router-link>, воспользуйтесь <router-link to="/category">каталогом</router-link> или поиском</p>
      <div class="cart-empty__controls">
        <router-link to="/" class="button-ui button-ui_white">Вернуться на главную</router-link>
        <router-link to="/category" class="button-ui button-ui_brand">Перейти в каталог</router-link>
        <div class="cart-empty__search"><a @click="isSearch=!isSearch" class="button-ui button-ui_brand">Добавить товары</a>
        </div>
        <search-module
            :notBuy="true" 
            v-if="isSearch"
            @close="isSearch=false"
          >
        </search-module>
      </div>
    </div>
</template>

<script>
    import { mapGetters } from 'vuex';
    import searchModule from '../../system/search-module.vue';
    export default {
        data() {
            return {
              isSearch: false
            }
        },
        computed: {
          ...mapGetters([
          ]),
        },
        methods: {
        },
        components: {
          searchModule
        }
    }
</script>

<style lang="less">
  @import '../../../../less/smart-grid.less';
  .cart-empty {
    box-shadow: 0 1px 2px 0 rgba(0,0,0,0.16);
    position: relative;
    background-color: #fff;
    color: #475d2f;
    border-radius: 8px;
    margin: 10px 0;
    padding: 10px;
    p{
      padding: 5px 0;
    }
    strong {
      font-weight: bold;
    }
    p a {
      color: #0094d9;
      text-decoration: none;
      &:hover, &:focus {
        color: #00608d;
        text-decoration: underline;
      }
    }
    &__controls {
      display: flex;
      flex-wrap: wrap;
      margin: 15px -10px 0 -10px;
      .sm(flex-direction, column);
      > a, > div > a {
        line-height: 40px;
        padding: 0 20px;
        width: 220px;
        margin-left: 10px;
        margin-bottom: 10px;
        &:hover {
          color: #fff;
        }
        &.button-ui_white:hover {
          background-image: linear-gradient(to bottom, #2a8cde, #165b92);
          box-shadow: inset 0 -2px 0 0 rgba(0, 0, 0, 0.2);
          color: #fff;
          border: none;
          font-weight: bold;
        }
        .sm-block({
          margin-right: 0;
          margin-bottom: 10px; 
        });
      }
      .search-module__container .wrap:before {
        left: 504px;
        .sm(left, 24px);
      }
    }
  }
</style>