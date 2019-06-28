<template>
    <div class="navbar-menu">
      <div class="header-top">
        <div class="container">
          <ul class="header-top-menu-list">
            <li>
              <i class="fas fa-phone"></i>
              <span class="header-top-phone">{{ settings['company_phone'] }}</span>
              <span class="header-top-calltime">{{ settings['company_calltime'] }}</span>
            </li>
            <li class="header-top-menu-shop">
              <dropdown-menu icon_after="fa fa-chevron-down">
                <template v-slot:activator>
                  Покупателям  
                </template>
                  <li><a>Как оформить заказ</a></li>
                  <li><a>Доставка</a></li>
                  <li><a>Способы оплаты</a></li>
                  <li><a>Бонусная программа</a></li>
                  <li><a>Узнать статус заказа</a></li>
                  <li><a>Обмен и возврат товара</a></li>
                  <li><a>Информация для юр. лиц</a></li>
              </dropdown-menu>
            </li>
            <uvedoml-component></uvedoml-component>
            <template v-if='!auth'>
              <li><router-link to="/login">Войти</router-link></li>
              <li><router-link to="/register">Регистрация</router-link></li>
            </template>
            <template v-else>
              <li>
                <dropdown-menu icon_after="fa fa-chevron-down" icon_before="fa fa-user">
                  <template v-slot:activator>
                    {{ userEmail }}
                  </template>  
                  <li><a>Профиль</a></li>
                  <li><a>Контрагенты</a></li>
                  <li><a>Бонусы</a></li>
                  <li><a>Заказы</a></li>
                  <li><a>Списки</a></li>
                  <li><a>Уведомления</a></li>
                  <li><a>Обратная связь</a></li>
                  <li><a @click.prevent="onExit">Выход</a>
                      <form ref='logoutform' id="logout-form" :action="this.$router.options.base+'logout'" method="POST" style="display: none;">
                       <input type="hidden" name="_token" id="csrf-token" :value="this.csrf">
                      </form>
                  </li>
                </dropdown-menu>
              </li>
            </template>
          </ul>
          <div class="basic-controls">
            <div  class="logo-container">
              <router-link to='/'>MICROSTONE</router-link>
            </div>
            <form class="main-search-form" method="get">
              <div class="main-search-form-container">
                <input type="text" class="main-search-form-input" name="search" placeholder="Поиск по каталогу" autocomplete="off">
                <span class="main-search-form-button-container">
                  <i class="fa fa-search"></i>
                </span>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
</template>

<script>
  import { mapGetters } from 'vuex';
  import UvedomlComp from './UvedomlComponent';
  import Dropdown from '../system/Dropdown';
    export default {
        data() {
            return {
                
            }
        }, 
        computed: {
          ...mapGetters([
            'settings',
            'auth',
            'userEmail',
            'csrf'
          ])
        }, 
        components: {
          'uvedoml-component': UvedomlComp,
          'dropdown-menu': Dropdown
        }, 
        methods: {
          onExit() {
            this.$refs['logoutform'].submit();
          }
        }
    }
</script>

<style>
  .header-top-menu-list > li {
    display: inline-block;
    margin-left: 15px;
    line-height: 40px;
  }
  .header-top-phone {
    font-weight: bold;
  }
  .header-top-calltime {
    padding-left: 10px;
  }
  .navbar-menu [class*="fa-"] {
    color: #7e7e7e;
    font-size: .8em;
  }
  .header-top .container {
    padding-right: 0;
    padding-left: 0;
  }
  .basic-controls .logo-container a {
    font-family: 'DigitalMono-7';
    font-weight: bold;
    font-style: normal;
    font-size: 40px;
    color: #0d61af;
    text-decoration: none;
    line-height: 40px;
    margin-top: 10px;
  }
  .basic-controls .logo-container {
    margin-left: 20px;
  }
  @media (min-width: 992px){
    .basic-controls { 
      display: none;
    }
  }
  @media (max-width: 991px) {
    .basic-controls {
      position: fixed;
      width: 100%;
      display: flex;
      height: 60px;
      background-color: #fff;
      border-bottom: solid 1px #d8d8d8;
    }
    .main-search-form-container {
      position: relative;
      background: #eaeaea;
      border-radius: 8px;
      height: 40px;
      font-size: 14px;
    }
    .main-search-form-input {
      background: #eaeaea;
      color: #333;
      padding-left: 12px;
      height: 95%;
      width: calc(100% - 45px);
      border-radius: 8px;
      border: none;
      outline: none;
    }
  }
  .basic-controls .main-search-form-button-container {
    color: #8c8c8c;
    position: absolute;
    right: 8px;
    top: 0;
    height: 30px;
    width: 40px;
    text-align: center;
    cursor: pointer;
    height: 39px;
  }
  .basic-controls .main-search-form-button-container i {
    position: absolute;
    top: 13px;
    font-size: 16px;
  }
  .basic-controls .main-search-form-button-container:hover i {
    color: #333;
  }
</style>