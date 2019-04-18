<template>
    <header :style="styleHeader">
      <top-menu></top-menu> 
      <bottom-menu></bottom-menu>        
    </header>
</template>

<script>
  import TopMenu from './Topmenu';
  import BottomMenu from './Bottommenu';
  import { mapGetters } from 'vuex';
    export default {
        data() {
            return {
                
            }
        },
        computed: {
          ...mapGetters([
            'isBottomMenuFixed',
            'getScreenState'
          ]),
          styleHeader() {
            return this.isBottomMenuFixed && this.getScreenState>1 ? {paddingTop: '60px'} : {paddingTop: 0}
          }
        }, 
        components: {
          'top-menu': TopMenu,
          'bottom-menu': BottomMenu
        }
    }
</script>

<style>
  
</style>