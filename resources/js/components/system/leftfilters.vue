<template>
    <div class="left-filters">
      <div class="left-filters__list">
        <filter-component v-for="el in filterItems" :key="el.id"
        :item="el"></filter-component>
      </div>
      <div class="left-filters__buttons">
        <div class="left-filters__buttons-main">
          <button class="button-ui button-ui_brand left-filters__button" @click="clickUse">Применить</button>
          <button class="button-ui button-ui_white left-filters__button" @click="clickRem">Сбросить</button>
        </div>
      </div>
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
            'filterItems',
            'categoryFilters',
            'getScreenState'
          ])
        },
        components: {
          'filter-component': filterComp
        },
        methods: {
          clickUse() {
            if (this.$router.currentRoute) {
              let obj = {};
              Object.assign(obj, this.categoryFilters);
              this.filterItems.forEach((el) => {
                if (el.filter_type=="Число") {
                  if (el.grp_data.minValue !== '' || el.grp_data.maxValue !== '') {
                    obj['f['+el.id_1s+']'] = ''+(el.grp_data.minValue==='' ? el.grp_data.min : el.grp_data.minValue)+'-'
                      +(el.grp_data.maxValue==='' ? el.grp_data.max : el.grp_data.maxValue);
                  } else {
                    delete obj['f['+el.id_1s+']'];
                  }
                } else {
                  if (el.grp_data.fChecked && el.grp_data.fChecked.length) {
                    obj['f['+el.id_1s+']'] = el.grp_data.fChecked.join('-');
                  } else {
                    delete obj['f['+el.id_1s+']'];
                  }
                }
              });
              this.$router.push({
                path: this.$router.currentRoute.path,
                query: obj
              });
              let scrTop = this.getScreenState == 1 ? 40 : 80;
              window.scrollTo({ top: scrTop, behavior: 'smooth' });
            }
          },
          clickRem() {
            if (this.$router.currentRoute) {
              let obj = {};
              Object.assign(obj, this.categoryFilters);
              this.filterItems.forEach((el) => {
                delete obj['f['+el.id_1s+']'];
              });
              this.$router.push({
                  path: this.$router.currentRoute.path,
                  query: obj
              });
              let scrTop = this.getScreenState == 1 ? 40 : 80;
              window.scrollTo({ top: scrTop, behavior: 'smooth' });
            }
          }
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
  .left-filters__buttons-main {
    width: 100%;
  }
  .left-filters__button {
    display: block;
    width: 90%;
    margin: 0 auto 8px;
    text-align: center;
  }
</style>