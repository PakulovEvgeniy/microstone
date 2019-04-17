<template>
    <div id="app">
        <header-component></header-component>
        <h1>{{ title }} {{ statename }} {{ str}}</h1>
        <router-view></router-view>
        <router-link :to="{ name: 'about' }">About</router-link>
        <router-link :to="{ name: 'contact' }">Contact</router-link>
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
        computed: {
          ...mapGetters([
            'settings'
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