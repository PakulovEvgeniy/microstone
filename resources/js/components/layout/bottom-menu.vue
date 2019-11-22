<template>
    <nav v-if="getScreenState>1" class="header-bottom" :class="{'header-fixed': isBottomMenuFixed}">
    <div class="container">
      <div class="header-bottom-container">
        <div  class="logo-container">
          <router-link v-tooltip.bottom="'Вернуться на главную страницу'" to='/'>
            MICROSTONE
          </router-link>
          <span @click="onMouseEnter2" v-show="nonVisibleAside" v-tooltip.bottom="'Каталог товаров'" class="logo-chevron"><i class="fa fa-chevron-down"></i></span>
        </div>
        <main-seach-form></main-seach-form>
        <div class="header-buttons">
          <header-buttons link="/account/waitlist" name="Лист ожидания" icon="fa-clock-o"></header-buttons>
          <header-buttons link="/account/wishlist" name="Мои списки" icon="fa-heart"></header-buttons>
          <header-buttons link="/account/cart" name="Корзина" icon="fa-shopping-cart"></header-buttons>
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
            'visBacdrop'
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
  .header-bottom {
    background: #fff;
    z-index: 1030;
    height: 60px;
    box-sizing: content-box;
    top: -64px;
    box-shadow: 0 0 10px 0 rgba(0, 0, 0, 0.25);
    &-container {
      display: flex;
      padding: 0;
      margin: 0;
      list-style: none;
      justify-content: flex-start;
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
  display: flex;
  min-width: 418px;
  justify-content: space-between;
  align-items: center;
  a {
    text-decoration: none;
    margin-left: 20px;   
  }
}

  .logo-container {
    min-width: 220px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    .logo-chevron {
      display: inline-block;
      width: 29px;
      height: 29px;
      text-align: center;
      padding-top: 7px;
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
  @media (max-width: 991px){
    .logo-container {
      min-width: 70px;
      width: 70px;
      span {
        display: block;
        font-size: 20px;
      }
    }
  }
</style>