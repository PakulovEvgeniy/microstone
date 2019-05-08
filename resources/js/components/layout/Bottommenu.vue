<template>
    <nav v-if="getScreenState>1" class="header-bottom" :class="{'header-fixed': isBottomMenuFixed}">
    <div class="container">
      <div class="header-bottom-container">
        <div  class="logo-container">
          <router-link v-tooltip.bottom="'Вернуться на главную страницу'" to='/'>MICROSTONE</router-link>
          <span class="logo-chevron"><i class="fa fa-chevron-down"></i></span>
        </div>
        <form class="main-search-form" method="get">
          <div class="main-search-form-container">
            <input type="text" class="main-search-form-input" name="search" placeholder="Поиск по каталогу">
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
              scrolled: 0  
            }
        }, 
        computed: {
          ...mapGetters([
            'isBottomMenuFixed',
            'getScreenState'
          ])
        },
        methods: {
          onScroll(e) {
            this.$store.commit('setScrolled', window.pageYOffset);  
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
   .logo-container .logo-chevron {
      width: 30px;
      background: none;
      display: inline-block;
      height: 60px;
      vertical-align: top;
      line-height: 60px;
      padding-left: 5px;
   }
   .logo-chevron i {
     opacity: 0.5;
     color: #fff;
   }

  .logo-container a {
    font-family: 'DigitalMono-7';
    font-weight: normal;
    font-style: normal;
    font-size: 40px;
    color: #fff;
    text-decoration: none;
    line-height: 40px;
    margin-top: 10px;
  }
</style>