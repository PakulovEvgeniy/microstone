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

<style lang="less">
  .header-bottom {
    background: rgba(29, 113, 184,0.9) !important;
    z-index: 1030;
    height: 60px;
    box-sizing: content-box;
    top: -64px;
    &-container {
      display: flex;
      padding: 0;
      margin: 0;
      list-style: none;
      justify-content: flex-start;
    }
  }

  .main-search-form {
    flex-grow: 1;
    padding: 10px 20px;
    &-container {
      display: table;
      width: 100%;
    }
    &-input {
      border-radius: 4px 0 0 4px;
      box-shadow: none;
      box-sizing: border-box;
      width: 100%;
      font-size: 15px;
    }
    &-button {
      border-radius: 0 4px 4px 0;
      box-shadow: none;
      position: relative;
      height: 40px;
      width: 60px;
      border: 1px solid #fff;
      background: #fff;
      outline: none;
      border-left: none;
      cursor: pointer;
      &-container {
        display: table-cell;
        z-index: 2;
        height: 40px;
        width: 1%;
        vertical-align: middle;
        i {
          color: #9898a1;
          font-size: 16px;
        }
        &::before {
          position: absolute;
          left: 0;
          top: 50%;
          margin-top: -10px;
          height: 20px;
          width: 0;
          content: " ";
          color: transparent;
          font-size: 0;
          border-left: solid 1px #e6e6e6;
        }
      }
    }
  }

.header-buttons {
  display: block;
  min-width: 418px;
  a {
    text-decoration: none;   
  }
  .btn-cart {
    box-shadow: 0 2px 0 0 #e3e3e3;
    background: #fff;
    border-color: #e3e3e3;
    min-width: 120px;
    margin-left: 20px;
    height: 39px;
    padding: 8px 12px 4px 12px;
    border-radius: 4px;
    text-align: center;
    line-height: 24px;  
    &:hover {
      background: #ededed;
      border-color: #e3e3e3;
    }
    i {
      color: #989696;
      font-size: .9em;
      margin-right: 10px;
      margin-top: 6px;
    }
    .price {
      color: #333;
      font-weight: 700;
      font-size: 15px;
      line-height: 24px;    
    }
  }
  .btn-info {
    color: #fff;
    font-size: 15px;
    line-height: 20px;
    font-weight: 400;
    padding: 20px 10px;
  }
  &:hover {
    background: rgba(255,255,255,0.2);
  }
  i {
    color: #fff;
    font-size: .9em;
    margin-right: 5px;
  }
}

  .logo-container {
    min-width: 220px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    .logo-chevron {
      display: inline-block;
      width: 30px;
      height: 30px;
      text-align: center;
      padding-top: 7px;
      border-radius: 50%;
      cursor: pointer;
      i {
        color: #fff;
        display: inline-block;
      }
      &:hover {
        background: #fff;
        opacity: 0.5;   
        i {
          color: rgb(122, 122, 122);
        }
      }
    }
    a {
      font-family: 'DigitalMono-7';
      font-weight: normal;
      font-style: normal;
      font-size: 40px;
      color: #fff;
      text-decoration: none;
      line-height: 40px;
    } 
  }
</style>