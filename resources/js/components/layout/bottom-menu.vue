<template>
    <nav v-if="getScreenState>1" class="header-bottom" :class="{'header-fixed': isBottomMenuFixed}">
    <div class="container">
      <div class="header-bottom-container">
        <div  class="logo-container">
          <router-link v-tooltip.bottom="'Вернуться на главную страницу'" to='/'>
            MICROSTONE
          </router-link>
          <span @click="onMouseEnter2" v-show="nonVisibleAside" v-tooltip.bottom="'Каталог товаров'" class="logo-chevron"><i class="fa" :class="{'fa-chevron-up': visBacdrop, 'fa-chevron-down': !visBacdrop}"></i></span>
        </div>
        <main-seach-form></main-seach-form>
        <div class="header-buttons">
          <header-buttons 
            link="/compare" 
            name="Сравнение" 
            icon="fa-bar-chart"
            :qty="countCompare"
            >
            </header-buttons>
          <header-buttons 
            link="/account/wishlist" 
            name="Мои списки" 
            icon="fa-heart"
            :qty = "countWishlist"
            >
          </header-buttons>
          <header-buttons 
            link="/account/cart" 
            name="Корзина" 
            icon="fa-shopping-cart"
            :qty="cartQty"
            ></header-buttons>
        </div>
      </div>
    </div>
  </nav>
</template>

<script>

import mainSeachForm from '../forms/main-seach-form.vue';
import headerButtons from '../system/header-buttons.vue';
  import { mapGetters } from 'vuex';
    export default {
        data() {
            return {
              scrolled: 0,
              idTime2: null  
            }
        }, 
        computed: {
          ...mapGetters([
            'isBottomMenuFixed',
            'getScreenState',
            'visBacdrop',
            'nonVisibleAside',
            'idTimeStartBack',
            'idTimeStopBack',
            'countWishlist',
            'countCompare',
            'cartQty'
          ])
        },
        components: {
          mainSeachForm,
          headerButtons
        },
        methods: {
          onScroll(e) {
            this.$store.state.scrolled=window.pageYOffset;  
          },
          onMouseEnter2() {
            this.$store.commit('setVisBacdrop',!this.visBacdrop);
          }
        },
        mounted() {
          window.addEventListener('scroll', this.onScroll);
          this.$store.commit('setScrolled', window.pageYOffset);
        },
        beforeDestroy() {
          window.removeEventListener('scroll', this.onScroll);
          this.$store.commit('setScrolled', 0);
        }
    }
</script>

<style lang="less">
@import '../../../less/vars';
@import '../../../less/smart-grid';
  .header-bottom {
    background: #fff;
    z-index: 1030;
    height: 60px;
    box-sizing: content-box;
    top: -64px;
    box-shadow: 0 0 10px 0 rgba(0, 0, 0, 0.25);
    .md(display, none);
    &-container {
      .row-flex();
      padding: 0;
      justify-content: flex-start;
      > * {
        .col();
      }
    }
    &.header-fixed {
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      box-shadow: 0 0 10px 0 rgba(0,0,0,0.25);
    }
  }

  .header-buttons {
    a {
      .col-ib();
      margin-left: 20px;
      line-height: 60px;   
    }
  }

  .logo-container {
    min-width: 220px;
    > * {
      .col-ib();
      vertical-align: middle;
      margin-top: 10px;
    }
    vertical-align: middle;
    .md-block({
      min-width: 70px;
      width: 70px;
      span {
        display: block;
        font-size: 20px;
      }
    });
    .logo-chevron {
      width: 29px;
      height: 29px;
      text-align: center;
      padding-top: 5px;
      border-radius: 50%;
      cursor: pointer;
      i {
        color: @main-color;
        display: inline-block;
      }
      &:hover {
        background-color: gray;
        opacity: 0.2;   
        i {
          color: #fff;
        }
      }
    }
    a {
      font-family: 'DigitalMono-7';
      font-weight: normal;
      font-style: normal;
      font-size: 40px;
      color: @main-color;
      text-decoration: none;
      line-height: 40px;
    } 
  }
</style>