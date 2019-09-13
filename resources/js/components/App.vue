<template>
    <div id="app">
      <vs-notify group="alert" position="top center" transition="ntf-top" :duration="4000"></vs-notify>
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
      <div :style="{right: leftTopBtn+'px'}" v-show="scrolled>200" id="scroll-top-button" @click="onClick">
        <i class="fas fa-arrow-up"></i>
      </div>
      <wait-load></wait-load>
    </div>
</template>

<script>
  import HeaderComponent from './layout/Header.vue';
  import CategoryMenu from './product/categorymenu.vue';
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
          }
        },
        computed: {
          ...mapGetters([
            'settings',
            'nonVisibleMain',
            'nonVisibleAside',
            'scrolled',
            'screenWidth'
          ]),
          leftTopBtn() {
            let res;
            res = Math.max(this.screenWidth/2 - 670, 30);
            return res;
          },
           statename() {
            return this.$store.state.name
           },
           str() {
              if (global && global.process && global.process.env.VUE_ENV == 'server') {
                return 'server'
              } 
              return this.$store.state;
           } 
        }
    }
</script>

<style>
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
  }
  #scroll-top-button i {
    font-size: 18px;
    color: #8c8c8c;
    margin-top: 3px;
  }
  #scroll-top-button i:hover {
    color: #333;
  }
  @media (min-width: 1200px) {
    #scroll-top-button {
      margin-bottom: 24px;
    }
  }
  @media (max-width: 1199px) and (min-width: 992px) {
    #scroll-top-button {
      bottom: 24px;
      right: 5px;
    }
  }
  @media (max-width: 991px) {
    #scroll-top-button {
      display: none;
    }
  }
</style>