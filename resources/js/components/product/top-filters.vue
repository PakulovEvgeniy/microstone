<template>
    <div class="top-filters">
      <div class="top-filters__wrap">
        <top-filter  :item="topFilters['order']" @input="onInput($event, topFilters['order'].name)" :curValue="categoryFilters[topFilters['order'].name]"></top-filter>
        <top-filter  :item="topFilters['group']" @input="onInput($event, topFilters['group'].name)" :curValue="categoryFilters[topFilters['group'].name]"></top-filter>
        <top-filter  :item="topFilters['stock']" @input="onInput($event, topFilters['stock'].name)" :curValue="categoryFilters[topFilters['stock'].name]"></top-filter>
      </div>
      <div class="top-filters__view-mode">
        <catalog-mode :value="curMode" @input="onInput($event,'mode')"></catalog-mode>
      </div>
    </div>
</template>

<script>
import { mapGetters } from 'vuex';
import topFilter from '../system/topfilter.vue';
import catalogMode from '../system/catalog-mode.vue';
    export default {
        data() {
            return {
              
            }
        },
        props: [
          'curMode'
        ], 
        components: {
          'top-filter': topFilter,
          'catalog-mode': catalogMode
        },
        computed: {
          ...mapGetters([
            'topFilters',
            'categoryFilters'
          ])
        },
        methods: {
          onInput(e, name) {
            if (this.$router.currentRoute) {
              this.$store.commit('setCategoryFilters',{
                name: name,
                value: e
              });
              this.$router.push({
                path: this.$router.currentRoute.path,
                query: this.categoryFilters
              });
            }
          }
        }
    }
</script>

<style>
  .top-filters {
    display: flex;
    flex-wrap: wrap;
    margin: 10px 20px;
  }
  .top-filters__wrap {
    padding-top: 7px;
    width: calc(100% - 50px);
  }
  .top-filters__view-mode {
    margin: 0 0 0 auto;
  }
  @media (max-width: 991px) {
    .top-filters {
      margin: 0 10px;
    }
    .top-filters__wrap {
      width: 100%;
      display: none;
    }
  }
</style>