<template>
    <div v-title="title" class="account">
      <div class="account__menu">
        <div class="account__menu_content">
          <ul class="account__menu_ul">
            <li v-for="it in menu" :key="it.id">
              <router-link :to="it.link" class="account__menu_link"><i class="fa" :class="it.icon"></i>{{it.name}}</router-link></li>
          </ul>
        </div>
      </div>
      <div class="account__content">
        <div class="account__content_block">
          <component :is="curComponent"></component>
        </div>
      </div>
    </div>
</template>

<script>
    import { mapGetters } from 'vuex';
    import profile from './accountcomps/profile.vue';
    import personal from './accountcomps/personal.vue';
    import contragents from './accountcomps/contragents.vue';
    import addresses from './accountcomps/addresses.vue';
    import orders from './accountcomps/orders.vue';
    import setup from './accountcomps/setup.vue';
    export default {
        data() {
            return {
                menu: [
                 {
                   id: 0,
                   icon: 'fa-home',
                   link: '/account',
                   name: 'Профиль'
                 },
                 {
                   id: 'personal',
                   icon: 'fa-user',
                   link: '/account/personal',
                   name: 'Личные данные'
                 },
                 {
                   id: 'contragents',
                   icon: 'fa-address-book',
                   link: '/account/contragents',
                   name: 'Контрагенты'
                 },
                 {
                   id: 'addresses',
                   icon: 'fa-address-card-o',
                   link: '/account/addresses',
                   name: 'Адреса доставки'
                 },
                 {
                   id: 'orders',
                   icon: 'fa-shopping-cart',
                   link: '/account/orders',
                   name: 'Заказы'
                 },
                 {
                   id: 'setup',
                   icon: 'fa-cog',
                   link: '/account/setup',
                   name: 'Настройки'
                 }
                ]
            }
        },
        computed: {
          ...mapGetters([
          ]),
          curMenu() {
            if (this.$route.params.id) {
              let elem = this.menu.find((el) => {
                return el.id == this.$route.params.id
              })
              if (elem) {
                return elem;
              }
            }
            return this.menu[0]; 
          },
          title() {
            return this.curMenu.name;
          },
          curComponent() {
            return this.curMenu.id === 0 ? 'profile' : this.curMenu.id;
          }
        },
        methods: {
            
        },
        components: {
          profile,
          personal,
          contragents,
          addresses,
          orders,
          setup
        },
        beforeRouteEnter (to, from, next) {
          next(vm => {
            vm.$store.commit('setNonVisibleAside', true);
          })
        }
    }
</script>

<style lang="less">
  .account {
    margin-top: 20px;
    &__menu {
      transition: left,0.3s,ease-out,200ms;
      width: 180px;
      float: left;
      &_ul {
        list-style: none;
        padding: 0;
        margin: 0;
      }
      &_link {
        border-radius: 8px;
        width: 180px;
        height: 48px;
        display: block;
        position: relative;
        margin: 4px 0;
        padding-top: 15px;
        padding-left: 42px;
        font-size: 14px;
        color: #4e4e4e !important;
        text-decoration: none !important;
        &.router-link-exact-active {
          background: #eaeaea;
          font-weight: bold;
          i {
            color: #333;
          }
          cursor: default;
        }
        &:hover {
          background: #eee;
        }
        i {
          font-size: 22px;
          position: absolute;
          top: 13px;
          left: 10px;
          color: #8c8c8c;
        }
      }
    }
    &__content {
      width: calc(100% - (180px + 22px));
      margin-left: 202px;
      &_block {
        border-radius: 8px;
        box-shadow: 0 1px 2px 0 rgba(0,0,0,0.16);
        border: solid 1px transparent;
        background-color: #fff;
        padding: 25px;
      }
    }
  }

  @media (max-width: 991px) {
    .account__menu {
      float: none;
      width: 100%;
      &_link {
        width: 100%;
        display: none;
        &.router-link-exact-active {
          display: block;
          background: #fff;
          border-radius: 0;
          position: fixed;
          top: 56px;
          left: 0;
          box-shadow: 0 4px 8px 0 rgba(0,0,0,0.16);
        }
      }
    }
    .account__content {
      margin-left: 0;
      width: 100%;
      margin-top: 70px;
    }
  }
</style>