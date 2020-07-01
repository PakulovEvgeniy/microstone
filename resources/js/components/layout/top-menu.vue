<template>
    <div class="navbar-menu">
      <div class="header-top">
        <div class="container">
          <ul class="header-top-menu-list">
            <li>
              <i class="fa fa-phone"></i>
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
                  <li>
                    <router-link to="/manufacturer">Производители</router-link>
                  </li>
              </dropdown-menu>
            </li>
            <uvedoml-component></uvedoml-component>
            <template v-if='!auth'>
              <li><router-link to="/login" class="white-link">Войти</router-link></li>
              <li><router-link to="/register" class="white-link">Регистрация</router-link></li>
            </template>
            <template v-else>
              <li>
                <dropdown-menu icon_after="fa fa-chevron-down" icon_before="fa fa-user">
                  <template v-slot:activator>
                    {{ userEmail }}
                  </template>  
                  <li><router-link to="/account">Профиль</router-link></li>
                  <li><router-link to="/account/contragents">Контрагенты</router-link></li>
                  <li><a>Бонусы</a></li>
                  <li><a>Заказы</a></li>
                  <li><a>Списки</a></li>
                  <li><a>Уведомления</a></li>
                  <li><a>Обратная связь</a></li>
                  <li><a @click.prevent="$refs['logoutform'].submit()">Выход</a>
                      <form ref='logoutform' id="logout-form" action="/logout" method="POST" style="display: none;">
                       <input type="hidden" name="_token" :value="csrf">
                      </form>
                  </li>
                </dropdown-menu>
              </li>
            </template>
          </ul>
          <div class="basic-controls">
            <div  class="logo-container">
              <router-link to='/'><span>MICRO</span><span>STONE</span></router-link>
            </div>
            <main-seach-form></main-seach-form>
            <div class="mobile-header-btns">
              <router-link class="btn-cart-link" to="/">
                <i class="fa fa-shopping-cart btn-cart-link__cart"></i>
                <span v-if="cartQty" class="btn-badge">{{cartQty}}</span>
              </router-link>
              
              <i @click="onClickOpen" class="fa btn-cart-link__menu" :class="{'has-notifies': hasNotifies, 'fa-bars': !menuOpen, 'fa-times': menuOpen}"></i>
            </div>
          </div>
        </div>
        <header-menu :open="menuOpen" @click="menuOpen=false"></header-menu>
      </div>
    </div>
</template>

<script>
  import { mapGetters } from 'vuex';
  import UvedomlComp from './uvedoml-component.vue';
  import Dropdown from '../system/drop-down.vue';
  import headerMenu from './header-menu.vue';
  import mainSeachForm from '../forms/main-seach-form.vue';
    export default {
        data() {
            return {
              menuOpen: false  
            }
        }, 
        computed: {
          ...mapGetters([
            'settings',
            'auth',
            'userEmail',
            'csrf',
            'cartQty',
            'hasNotifies',
            'bodyBlocked'
          ])
        }, 
        components: {
          'uvedoml-component': UvedomlComp,
          'dropdown-menu': Dropdown,
          headerMenu,
          mainSeachForm
        }, 
        methods: {
          onClickOpen() {
            if (!this.menuOpen && this.bodyBlocked) {
              return;
            }
            this.menuOpen = !this.menuOpen;
          }
        },
        beforeDestroy() {
          this.$store.commit('setBodyBlocked', false);
        },
        watch: {
          menuOpen(val) {
            this.$store.commit('setBodyBlocked', val);
          }
        }
    }
</script>

<style lang="less">
@import '../../../less/vars.less';
@import '../../../less/smart-grid.less';

.navbar-menu {
  background: rgba(29, 113, 184, 0.9);
  border: none;
  color: #fff;
  a.menu-dropdown-target, a.white-link {
    color: #fff;
    &:hover {
      opacity: 0.7;
      ~ i {
        opacity: 0.7;
      }
    }
  }
  [class*="fa-"] {
    font-size: .8em;
  }
}

.header-top {
  min-height: 40px;
  max-height: 40px;
  &-menu-list {
    text-align: right;
    > li {
      .col-ib();
      line-height: 40px;
      margin-left: 10px;
    }
    .fa-phone {
      font-size: 12px !important;
    }
    .md(display,none);
  }
  &-phone {
    font-weight: bold;
  }
  &-calltime {
    padding-left: 10px;
  }
  .md-block({
    min-height: 60px;
    max-height: 60px;
  });
  .container {
    .md-block({
      padding-left: 0;
      padding-right: 0;
    });
  }
  .basic-controls {
    display: none;
    position: fixed;
    width: 100%;
    height: 60px;
    background-color: #fff;
    border-bottom: solid 1px #d8d8d8;
    .btn-badge {
      top: 10px;
    }
    .logo-container {
      margin-left: 40px;
      a {
        font-weight: bold;
        font-size: 40px;
        color: #0d61af;
        line-height: 20px;
      }
    }
    .mobile-header-btns {
      position: relative;
      height: 60px;
      display: flex;
      > * {
        display: block;
        text-align: center;
      }
      .md(right, -15px);
      .btn-cart-link {
        color: #ffa218;
        background: transparent;
        font-weight: bold;
        text-decoration: none;
        position: relative;
        width: 44px;
        &__cart {
          color: #8c8c8c;
          font-size: 20px;
          margin-right: 8px;
          line-height: 60px;
        }
        &__menu {
          color: #8c8c8c;
          height: 60px;
          margin: 0;
          transition: background-color .1s ease;
          width: 44px;
          font-size: 21px;
          cursor: pointer;
          line-height: 60px;
          &.fa-times {
            font-size: 25px;
          }
          &.has-notifies:after {
            background-color: #1D71B8;
            border: 3px solid #fff;
            border-radius: 10px;
            content: ' ';
            height: 13px;
            position: absolute;
            right: 5px;
            top: 14px;
            width: 13px;
          }
        }
      }
    }
    .md(display, flex);
  }
}

  
</style>