<template>
    <div v-title="title" class="account">
      <div class="account__menu">
        <div v-if="auth" class="account__menu_content">
          <i class="fa account__menu_menu" :class="{'fa-bars': !open, 'fa-times': open}" @click="onClickOpen()"></i>
          <account-mobile-menu :open="open" :menu="menu" @click="open=false"></account-mobile-menu>
          <ul class="account__menu_ul">
            <li v-for="it in menu" :key="it.id">
              <router-link :to="it.link" class="account__menu_link" :class="{'open': open, 'second': it.id!=0}" :disable="true"><i class="fa" :class="it.icon"></i>{{it.name}}</router-link>
            </li>
          </ul>
        </div>
      </div>
      <div class="account__content" :class="{'not-auth': !auth}">
        <h1 :class="{show: !auth}">{{title}}</h1>
        <div class="account__content_block" :class="{wishlist: curComponent=='wishlist'}">
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
    import contragentsAdd from './accountcomps/contragents-add.vue';
    import addressesAdd from './accountcomps/addresses-add.vue';
    import addresses from './accountcomps/addresses.vue';
    import orders from './accountcomps/orders.vue';
    import bonuses from './accountcomps/bonus.vue';
    import notification from './accountcomps/notification.vue';
    import wishlist from './accountcomps/wishlist.vue';
    import waitlist from './accountcomps/waitlist.vue';
    import achievements from './accountcomps/achievements.vue';
    import feedback from './accountcomps/feedback.vue';
    import setup from './accountcomps/setup.vue';
    import accountMobileMenu from './accountcomps/account-mobile-menu.vue';
    import changepsw from './accountcomps/changepsw.vue';
    export default {
        data() {
            return {
                open: false,
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
                   name: 'Контрагенты',
                   child: [
                     {
                         id: 'add',
                         comp: 'contragents-add',
                         name: 'Добавление контрагента'
                     },
                     {
                         id: 'edit',
                         comp: 'contragents-add',
                         name: 'Редактирование контрагента'
                     }
                   ]
                 },
                 {
                   id: 'addresses',
                   icon: 'fa-address-card-o',
                   link: '/account/addresses',
                   name: 'Адреса доставки',
                   child: [
                     {
                         id: 'add',
                         comp: 'addresses-add',
                         name: 'Добавление адреса'
                     },
                     {
                         id: 'edit',
                         comp: 'addresses-add',
                         name: 'Редактирование адреса'
                     }
                   ]
                 },
                 {
                   id: 'orders',
                   icon: 'fa-shopping-cart',
                   link: '/account/orders',
                   name: 'Заказы'
                 },
                 {
                   id: 'bonuses',
                   icon: 'fa-pinterest-p',
                   link: '/account/bonuses',
                   name: 'Бонусы'
                 },
                 {
                   id: 'notification',
                   icon: 'fa-bell',
                   link: '/account/notification',
                   name: 'Уведомления'
                 },
                 {
                   id: 'wishlist',
                   icon: 'fa-heart',
                   link: '/account/wishlist',
                   name: 'Мои списки'
                 },
                 {
                   id: 'waitlist',
                   icon: 'fa-clock-o',
                   link: '/account/waitlist',
                   name: 'Лист ожидания'
                 },
                 {
                   id: 'achievements',
                   icon: 'fa-star',
                   link: '/account/achievements',
                   name: 'Достижения'
                 },
                 {
                   id: 'feedback',
                   icon: 'fa-commenting',
                   link: '/account/feedback',
                   name: 'Обратная связь'
                 },
                 {
                   id: 'changepsw',
                   icon: 'fa-sign-in',
                   link: '/account/changepsw',
                   name: 'Сменить пароль'
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
            'bodyBlocked',
            'auth'
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
            if (this.$route.params.act) {
              if (this.curMenu.child) {
                let elem = this.curMenu.child.find((el) => {
                  return el.id == this.$route.params.act
                })
                if (elem) {return elem.name}
              }
            }
            return this.curMenu.name;
          },
          curComponent() {
            let cur = this.curMenu.id;
            if (this.$route.params.act) {
              if (this.curMenu.child) {
                let elem = this.curMenu.child.find((el) => {
                  return el.id == this.$route.params.act
                })
                if (elem) {cur = elem.comp}
              }
            }
            return cur === 0 ? 'profile' : cur;
          }
        },
        methods: {
          onClickOpen() {
            if (!this.open && this.bodyBlocked) {
              return;
            }
            this.open = !this.open;
          }  
        },
        components: {
          profile,
          personal,
          contragents,
          contragentsAdd,
          addresses,
          addressesAdd,
          orders,
          setup,
          bonuses,
          notification,
          wishlist,
          waitlist,
          achievements,
          feedback,
          accountMobileMenu,
          changepsw
        },
        beforeRouteEnter (to, from, next) {
          next(vm => {
            vm.$store.commit('setNonVisibleAside', true);
          })
        },
        beforeDestroy() {
          this.$store.commit('setBodyBlocked', false);
        },
        watch: {
          open(val) {
            this.$store.commit('setBodyBlocked', val);
          }
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
        &.router-link-exact-active, &.router-link-active.second {
          background: #eaeaea;
          font-weight: bold;
          i {
            color: #333;
          }
          &.open {
            border-bottom: 1px solid rgba(0, 0, 0, 0.15);
          }
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
      &_menu {
        display: none;
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
      h1 {
        font-size: 28px;
        font-weight: normal;
        margin-top: 0;
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
        &.router-link-exact-active, &.router-link-active.second {
          display: block;
          background: #fff;
          border-radius: 0;
          position: fixed;
          top: 56px;
          left: 0;
          box-shadow: 0 4px 8px 0 rgba(0,0,0,0.16);
          z-index: 100;
          pointer-events: none;
        }
      }
      &_menu {
        display: block;
        position: fixed;
        width: 30px;
        height: 30px;
        text-align: center;
        line-height: 30px;
        right: 10px;
        top: 68px;
        font-size: 21px;
        cursor: pointer;
        z-index: 101;
        color: #8c8c8c;
      }
    }
    .account__content {
      margin-left: 0;
      width: 100%;
      margin-top: 70px;
      h1 {
        display: none;
      }
      h1.show {
        display: block;
      }
      &.not-auth {
        margin-top: 0;
      }
      &_block.wishlist {
        border-radius: unset;
        box-shadow: none;
        border: none;
        padding: 0;
      }
    }
  }
</style>