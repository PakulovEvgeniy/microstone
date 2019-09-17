<template>
    <div class="header-menu" :class="{'open' : open}">
     <ul class="header-menu-list" @click="onClick()">
       <li class="menu-item-phone">
         <a :href="'tel:'+settings['company_phone']"><i class="fas fa-phone icon"></i>{{settings['company_phone']}}<span class="phone-worktime">{{settings['company_calltime']}}</span></a>
       </li>
       <li class="user-section">
         <router-link v-if="!auth" to="/login">
           <i class="fas fa-user icon"></i>
           Войти в личный кабинет
         </router-link>
         <div v-else class="user-wrapper">
           <router-link class="user" to="/account">
              <i class="fas fa-user icon"></i>
              {{userEmail}}
           </router-link>
           <a class="logout" @click.prevent="$refs['logoutform2'].submit()">
             <i class="fas fa-sign-out-alt"></i>
             <br>
             <span>Выход</span>
             <form ref='logoutform2' id="logout-form2" action="/logout" method="POST" style="display: none;">
                <input type="hidden" name="_token" id="csrf-token" :value="csrf">
             </form>
           </a>
         </div>
       </li>
       <li class="menu-item-catalog">
         <router-link to="/category">
           <i class="fas fa-shopping-bag icon"></i>
           Каталог
         </router-link>
       </li>
       <li class="menu-item-wishlist">
         <router-link class="btn-wishlist-link" to="/wishlist">
           <i class="far fa-heart icon"></i>
           Избранное
           <span v-show="wishlist.items.length" class="btn-wishlist-link__badge">{{wishlist.items.length}}</span>
         </router-link>
       </li>
       <li class="menu-item-waitlist">
         <router-link class="btn-waitlist-link" to="/waitlist">
           <i class="far fa-clock icon"></i>
           Ожидаемое
           <span v-show="waitlist.items.length" class="btn-waitlist-link__badge">{{waitlist.items.length}}</span>
         </router-link>
       </li>
       <li class="menu-item-actions">
         <router-link class="btn-actions-link" to="/actions">
          <i class="fas fa-tags icon"></i>
           Акции
         </router-link>
       </li>
       <li class="menu-item-home">
         <router-link class="btn-home-link" to="/">
          <i class="fas fa-home icon"></i>
           Главная страница
         </router-link>
       </li>
       <li class="menu-item-corporative">
         <router-link to="/about">
          Компания Микростоун
         </router-link>
       </li>
       <li class="menu-item-corporative">
         <router-link to="/feedback">
          Обратная связь
         </router-link>
       </li>
       <li class="menu-item-corporative">
         <router-link to="/for-buyers">
          Покупателям
         </router-link>
       </li>
     </ul>
    </div>
</template>

<script>
  import { mapGetters } from 'vuex';
    export default {
        data() {
            return {
            }
        },
        props: [
          'open'
        ], 
        computed: {
          ...mapGetters([
            'settings',
            'auth',
            'userEmail',
            'csrf',
            'wishlist',
            'waitlist'
          ])
        },
        methods: {
          onClick() {
            this.$emit('click');
          }
        }
    }
</script>

<style lang="less">
@import '../../../less/vars';
  .header-menu {
    transition: right .3s ease-out 200ms;
    opacity: 1;
    display: none;
    top: 60px;
    &-list {
      margin: 0;
      transition: right 200ms ease-in-out 0ms;
      width: 100%;
      margin-top: 0;
      list-style: none;
      padding: 0;
      text-align: left;
      &:after, &:before {
        content: " ";
        display: table;
      }
      &:after {
        clear: both;
      }
      >li {
        display: block;
        position: relative;
        border-bottom: 1px solid rgba(0,0,0,0.15);
        line-height: 60px;
        text-align: center;
        width: 25%;
        a {
          overflow: hidden;
          text-overflow: ellipsis;
          white-space: nowrap;
          color: @block-color;
        } 
      }
    }
    &.open {
      right: 0;
      .header-menu-list {
        box-shadow: 0 2px 4px 0 rgba(0,0,0,0.25);
        right: 0;
      }
    }

    li.user-section {
      .user-wrapper {
        display: table !important;
        margin: 10px 0;
        a {
          display: table-cell;
          white-space: nowrap;
          overflow: hidden;
          text-overflow: ellipsis;
          position: relative;
          width: 100%;
          line-height: 40px;
          &.user {
            max-width: 242px;
          }
          &.logout {
            border-left: solid 1px #d9d9d9;
            font-size: 13px;
            width: 78px;
            text-align: center;
            line-height: 13px;
            color: gray;
            vertical-align: middle;
            i {
              font-size: 17px;
            }
          }
        }
      }
    }
  }

  @media (max-width: 991px) {
    .header-menu {
      display: block;
      background: rgba(0,0,0,0.5);
      right: -100%;
      width: 100%;
      z-index: 1100;
      border-top: none;
      position: fixed;
      bottom: 0;
      overflow: auto;
      -webkit-overflow-scrolling: touch;
    }

    .header-menu-list {
      float: right;
      border-top: none;
      background: #fff;
      left: -100%;
    }
    .header-menu .header-menu-list>li {
      float: right;
      width: 100%;
      border-left: none;
    }
    .header-menu .header-menu-list>li a {
      box-shadow: none;
      position: relative;
      display: block;
      width: 100%;
      text-align: left;
      font-weight: normal;
      padding: 0 20px;
      line-height: 60px;
      font-size: 18px;
      border: none 0;
    }
    .header-menu .header-menu-list>li a:hover {
      color: #333;
      background-color: #f5f5f5;
      text-decoration: none;
    }
    .header-menu .header-menu-list>li .icon {
      font-size: 16px;
      margin-right: 8px;
      display: inline;
    }
    .header-menu .header-menu-list>li .phone-worktime {
      font-size: 13px;
      position: absolute;
      right: 20px;
      top: 0;
      color: gray;
    }
    .header-menu .header-menu-list>li.menu-item-catalog a {
      background: rgba(29, 113, 184,0.8);
      color: #fff !important;
    }
    .header-menu .header-menu-list>li.menu-item-catalog a i {
      color: #fff !important;
    }
    .header-menu .header-menu-list>li.menu-item-catalog a:hover {
      background: rgba(29, 113, 184);
      color: #fff !important;
    }
    .btn-wishlist-link__badge, .btn-waitlist-link__badge {
      border-radius: 13px;
      display: inline-flex;
      justify-content: center;
      align-items: center;
      width: 20px;
      height: 20px;
      margin-left: 5px;
      font-size: 12px;
      font-weight: bolder;
      color: #fff;
      vertical-align: text-top;
      background-image: linear-gradient(to top, #1D71B8, #2288DB);
    }
    .header-menu .header-menu-list>li.menu-item-corporative {
      border-bottom: none 0;
    }
    .header-menu .header-menu-list>li.menu-item-corporative a {
      background: gray;
      color: #fff !important;
    }
    .header-menu .header-menu-list>li.menu-item-corporative a:hover {
      background: #737373 !important;
      color: #fff !important;
    }
  }
  @media (max-width: 991px) and (min-width: 768px) {
    .header-menu-list {
      width: 380px;
    }
  }
</style>