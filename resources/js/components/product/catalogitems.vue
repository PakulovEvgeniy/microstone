<template>
    <div class="catalog-items">
      <desktop-controls></desktop-controls>
    </div>
</template>

<script>
	import desktopControls from './desktopcontrols.vue';
    export default {
        data() {
            return {
            }
        },
        components: {
        	'desktop-controls': desktopControls
        }
    }
</script>

<style>
  
</style>