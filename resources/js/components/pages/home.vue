<template>
    <div v-title="title">
      <banner :bannerList="banners"></banner>
    </div>
</template>

<script>
    import { mapGetters } from 'vuex';
    import Banner from '../system/banner.vue';
    export default {
        data() {
            return {
                title: 'Интернет-магазин "Микростоун"'
            }
        },
        computed: {
          ...mapGetters([
            'banners'
          ]),
        },
        methods: {
            
        },
        components: {
            'banner': Banner
        }
    }
</script>

<style>
    
</style>