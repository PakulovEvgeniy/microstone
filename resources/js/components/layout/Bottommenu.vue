<template>
    <nav v-if="getScreenState>1" class="header-bottom" :class="{'header-fixed': isBottomMenuFixed}">
    <div class="container">
      <div class="header-bottom-container">
        <div  class="logo-container">
          <router-link v-tooltip.bottom="'Вернуться на главную страницу'" to='/'>MICROSTONE</router-link>
          <span @click="onMouseEnter2" v-show="nonVisibleAside" v-tooltip.bottom="'Каталог товаров'" class="logo-chevron"><i class="fa fa-chevron-down"></i></span>
        </div>
        <form class="main-search-form" method="get">
          <div class="main-search-form-container">
            <input type="text" class="main-search-form-input" name="search" placeholder="Поиск по каталогу" autocomplete="off">
            <span class="main-search-form-button-container">
              <button tabindex="2" type="submit" class="main-search-form-button"><i class="fa fa-search"></i></button>
            </span>
          </div>
        </form>
        <div class="header-buttons">
          <a class="btn-info">
            <i class="far fa-clock"></i>
            <span>Лист ожидания</span>
          </a>
          <a class="btn-info">
            <i class="far fa-heart"></i>
            <span>Мои списки</span>
          </a>
          <a class="btn-cart">
            <i class="fa fa-shopping-cart"></i>
            <span class="price">
              <span>Корзина</span>
            </span>
          </a>
        </div>
      </div>
    </div>
  </nav>
</template>

<script>
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

<style>
   .logo-container {
    min-width: 220px;
    display: flex;
    justify-content: space-between;
    align-items: center;
   }
   .logo-container .logo-chevron {
      display: inline-block;
      width: 30px;
      height: 30px;
      text-align: center;
      padding-top: 7px;
      border-radius: 50%;
      cursor: pointer;
   }
   .logo-chevron i {
     color: #fff;
     display: inline-block;
   }
  .logo-chevron:hover {
     background: #fff;
     opacity: 0.5;
  }
  .logo-chevron:hover i {
    color: rgb(122, 122, 122);
  }
  .logo-container a {
    font-family: 'DigitalMono-7';
    font-weight: normal;
    font-style: normal;
    font-size: 40px;
    color: #fff;
    text-decoration: none;
    line-height: 40px;
  }
</style>