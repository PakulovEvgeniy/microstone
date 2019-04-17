<template>
    <header>
      <top-menu></top-menu> 
      <bottom-menu></bottom-menu>        
    </header>
</template>

<script>
  import TopMenu from './Topmenu';
  import BottomMenu from './Bottommenu';
    export default {
        data() {
            return {
                
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