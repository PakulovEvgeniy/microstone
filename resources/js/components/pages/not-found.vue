<template>
    <div class="not_found_page" v-title="title">
      <not-found-comp></not-found-comp>
    </div>
</template>

<script>
    import { mapGetters } from 'vuex';
    import notFoundComp from './general/not-found-comp.vue';
    export default {
        data() {
            return {
            }
        },
        computed: {
          ...mapGetters([
            'settings'
          ]),
          title() {
            return this.settings.company_name;
          }
        },
        methods: {
        },
        components: {
            notFoundComp
        },
        beforeRouteEnter (to, from, next) {
          next(vm => {
            vm.$store.commit('setNonVisibleAside', false);
          })
        }
    }
</script>

<style lang="less">
</style>