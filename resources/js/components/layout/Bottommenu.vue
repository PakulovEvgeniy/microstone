<template>
    <nav class="header-bottom">
    <div class="container">
      <div class="header-bottom-container">
        <div class="logo-container">
          <router-link to='/'>MICROSTONE</router-link>
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
                
            }
        }, 
        computed: {
          ...mapGetters([
            'settings'
          ])
        }
    }
</script>

<style>
  
</style>