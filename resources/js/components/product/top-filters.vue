<template>
    <div class="top-filters">
      <div class="top-filters__wrap" :class="{'showed': tf_showed}">
        <top-filter  :item="topFilters['order']" @input="onInput($event, topFilters['order'].name)" :curValue="categoryFilters[topFilters['order'].name]"></top-filter>
        <top-filter  :item="topFilters['group']" @input="onInput($event, topFilters['group'].name)" :curValue="categoryFilters[topFilters['group'].name]"></top-filter>
        <top-filter  :item="topFilters['stock']" @input="onInput($event, topFilters['stock'].name)" :curValue="categoryFilters[topFilters['stock'].name]"></top-filter>
      </div>
      <topfilters-picked></topfilters-picked>
      <div class="top-filters__view-mode">
        <catalog-mode :value="curMode" @input="onInput($event,'mode')"></catalog-mode>
      </div>
    </div>
</template>

<script>
import { mapGetters } from 'vuex';
import topFilter from '../system/topfilter.vue';
import catalogMode from '../system/catalog-mode.vue';
import topfiltersPicked from './topfilters_picked.vue';
    export default {
        data() {
            return {
              
            }
        },
        props: [
          'curMode',
          'tf_showed'
        ], 
        components: {
          'top-filter': topFilter,
          'catalog-mode': catalogMode,
          'topfilters-picked': topfiltersPicked
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
              let obj = {};
              Object.assign(obj, this.categoryFilters);
              obj[name] = e.value;
              if (name=='stock' && obj['page'] !== undefined) {
                obj['page'] = 1;
              }
              this.$router.push({
                path: this.$router.currentRoute.path,
                query: obj
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
    display: none;
  }
  .top-filters__view-mode {
    margin: 0 0 0 auto;
  }
  .top-filters__wrap.showed {
    display: block;
  }
  @media (max-width: 991px) {
    .top-filters {
      margin: 0 10px;
    }
    .top-filters__wrap {
      width: 100%;
    }
    .catalog-mode {
      display: none;
    }
  }
  @media (min-width: 992px) {
    .top-filters__wrap {
      display: block;
    }
  }
</style>