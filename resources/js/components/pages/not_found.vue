<template>
    <div>
      
    </div>
</template>

<script>
    import { mapGetters } from 'vuex';
    export default {
        data() {
            return {
                title: 'Интернет-магазин "Микростоун"'
            }
        },
        computed: {
          ...mapGetters([
          ])
        },
        methods: {
            
        },
        components: {
        },
        beforeRouteEnter (to, from, next) {
          next(vm => {
            vm.$store.commit('setNonVisibleAside', false);
          })
        }
    }
</script>

<style>
    
</style>