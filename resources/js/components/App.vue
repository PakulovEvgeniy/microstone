<template>
    <div id="app">
      <vs-notify group="alert" position="top center" transition="ntf-top" :duration="4000"></vs-notify>
      <div v-if = "!nonVisibleMain">
        <header-component></header-component>
        <h1>{{ title }} {{ statename }} {{ str}}</h1>
      </div>
      <router-view></router-view>
      <div v-if = "!nonVisibleMain">
        <router-link :to="{ name: 'about' }">About</router-link>
        <router-link :to="{ name: 'contact' }">Contact</router-link>
      </div>
    </div>
</template>

<script>
  import HeaderComponent from './layout/Header.vue';
  import { mapGetters } from 'vuex';

    export default {
        data() {
            return {
                title: 'Welcme To My Site'
            }
        }, 
        components: {
          'header-component': HeaderComponent
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
            'nonVisibleMain'
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