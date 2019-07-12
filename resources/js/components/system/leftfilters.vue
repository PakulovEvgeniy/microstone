<template>
    <div class="left-filters">
      <div class="left-filters__list">
        <filter-component v-for="el in filterItems" :key="el.id"
        :item="el" :itemGrp="grpDataOfCategory[el.id_1s] || {}"></filter-component>
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
            'grpDataOfCategory',
            'categoryFilters'
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
              for (let key in this.grpDataOfCategory) {
                let el = this.grpDataOfCategory[key];
                if (el.filter_type=="Число") {
                  if (el.minValue !== '' || el.maxValue !== '') {
                    obj['f['+key+']'] = ''+(el.minValue==='' ? el.min : el.minValue)+'-'
                      +(el.maxValue==='' ? el.max : el.maxValue);
                  } else {
                    delete obj['f['+key+']'];
                  }
                } else {
                  if (el.fChecked && el.fChecked.length) {
                    obj['f['+key+']'] = el.fChecked.join('-');
                  } else {
                    delete obj['f['+key+']'];
                  }
                }
              }

              this.$router.push({
                path: this.$router.currentRoute.path,
                query: obj
              });
            }
          },
          clickRem() {
            if (this.$router.currentRoute) {
              let obj = {};
              Object.assign(obj, this.categoryFilters);
              for (let key in this.grpDataOfCategory) {
                delete obj['f['+key+']'];
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