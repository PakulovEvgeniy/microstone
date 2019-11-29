<template>
    <div id="app">
      <vs-notify 
        group="alert" 
        position="top center" 
        transition="ntf-top" 
        :duration="4000">
      </vs-notify>
      <div v-if = "!nonVisibleMain">
        <header-component></header-component>
      </div>
      <div class="container">
        <aside v-if = "!nonVisibleMain && !nonVisibleAside">
          <category-menu></category-menu>
        </aside>
        <category-menu v-if="!nonVisibleMain && nonVisibleAside"></category-menu>
        <main>
          <router-view></router-view>
        </main>
      </div>
      <div v-if = "!nonVisibleMain">
        <router-link :to="{ name: 'about' }">About</router-link>
        <router-link :to="{ name: 'contact' }">Contact</router-link>
      </div>
      <div 
        :style="{right: leftTopBtn+'px'}" 
        v-show="scrolled>200" 
        id="scroll-top-button" 
        @click="onClick">
        <i class="fa fa-arrow-up"></i>
      </div>
      <wait-load></wait-load>
    </div>
</template>

<script>
  import HeaderComponent from './layout/header.vue';
  import CategoryMenu from './pages/product/category-menu.vue';
  import { mapGetters } from 'vuex';
  import waitLoad from './system/wait-load.vue';

    export default {
        data() {
            return {
            }
        }, 
        components: {
          'header-component': HeaderComponent,
          'category-menu': CategoryMenu,
          'wait-load': waitLoad
        },
        mounted() {
          this.$store.commit('setScreenWidth',window.innerWidth);
          this.$store.commit('setScreenHeight',window.innerHeight);
          window.addEventListener('resize', this.onResize);
          if (!this.auth) {
            this.$store.dispatch('restoreWishList');  
          }
          this.$store.commit('setMounted', true);
        },
        beforeDestroy () {
          window.removeEventListener('resize', this.onResize);
        },
        methods: {
          onResize() {
            this.$store.state.screenWidth = window.innerWidth;
            this.$store.state.screenHeight = window.innerHeight;
          },
          onClick() {
            window.scrollTo({ top: 0, behavior: 'smooth' });
          },
          blockBody(scrSt, val) {
            let body = document.querySelector('body');
            if (body) {
              if (scrSt<2) {
                if (val) {
                  body.classList.add('blocked');
                } else {
                  body.classList.remove('blocked');
                }
              } else {
                body.classList.remove('blocked');
              }
            }
          }
        },
        computed: {
          ...mapGetters([
            'settings',
            'nonVisibleMain',
            'nonVisibleAside',
            'scrolled',
            'screenWidth',
            'bodyBlocked',
            'getScreenState',
            'auth'
          ]),
          leftTopBtn() {
            let res;
            res = Math.max(this.screenWidth/2 - 670, 30);
            return res;
          }
        },
        watch: {
          bodyBlocked(val) {
            this.blockBody(this.getScreenState, val);
          },
          getScreenState(val) {
            this.blockBody(val, this.bodyBlocked);
          }
        }
    }
</script>

<style lang="less">
  @import '../../less/vars';
  main {
    position: relative;
  }
  #scroll-top-button {
    box-shadow: 0 0 10px 0 rgba(0,0,0,0.25);
    border-radius: 12px;
    width: 60px;
    height: 60px;
    cursor: pointer;
    position: fixed;
    z-index: 1000;
    bottom: 0;
    right: 0;
    display: flex;
    justify-content: center;
    align-items: center;
    border-radius: 12px;
    opacity: .88;
    background: #fff;
    i {
      font-size: 18px;
      color: #8c8c8c;
      margin-top: 3px;
      &:hover {
        color: @block-color;    
      }
    }
    @media (min-width: 1200px) {
      margin-bottom: 24px;
    }
    @media (max-width: 1199px) and (min-width: 992px) {
      bottom: 24px;
      right: 5px;
    }
    @media (max-width: 991px) {
      display: none;
    }
  }
  
  
  
</style>