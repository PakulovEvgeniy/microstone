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
                  <li><a href="">Выход</a></li>
                </dropdown-menu>
              </li>
            </template>
          </ul>
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
            'userEmail'
          ])
        }, 
        components: {
          'uvedoml-component': UvedomlComp,
          'dropdown-menu': Dropdown
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
</style>