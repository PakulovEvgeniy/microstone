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
    </div>
</template>

<script>
  import HeaderComponent from './layout/Header.vue';
  import CategoryMenu from './product/categorymenu.vue';
  import { mapGetters } from 'vuex';

    export default {
        data() {
            return {
                
            }
        }, 
        components: {
          'header-component': HeaderComponent,
          'category-menu': CategoryMenu
        },
        mounted() {
          this.$store.commit('setScreenWidth',window.innerWidth);
          window.addEventListener('resize', this.onResize);
        },
        beforeDestroy () {
          window.removeEventListener('resize', this.onResize);
        },
        methods: {
          onResize() {
            this.$store.commit('setScreenWidth',window.innerWidth);
          }
        },
        computed: {
          ...mapGetters([
            'settings',
            'nonVisibleMain',
            'nonVisibleAside'
          ]),
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
</style>