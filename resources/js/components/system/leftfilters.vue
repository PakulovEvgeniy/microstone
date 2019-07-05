<template>
    <div class="left-filters">
      <div class="left-filters__list">
        <filter-component v-for="el in filterItems" :key="el.id"
        :item="el"></filter-component>
      </div>
      <div class="left-filters__buttons"></div>
    </div>
</template>

<script>
import { mapGetters } from 'vuex';
import filterComp from './filter-comp.vue'; 
    export default {
        data() {
            return {
            }
        },
        computed: {
          ...mapGetters([
            'filterItems'
          ])
        },
        components: {
          'filter-component': filterComp
        }
    }
</script>

<style>
  .left-filters {
    position: relative;
    border-radius: 8px;
    box-shadow: 0 1px 2px 0 rgba(0,0,0,0.16);
    border: solid 1px transparent;
    background: #fff;
  }
  .left-filters__buttons {
    display: flex;
    flex-direction: column;
    align-items: center;
    margin: 20px 0;
  }
</style>