<template>
    <div v-show="needFilters.length" class="top-filters__picked">
      <span v-if="needFilters.length" class="picked-filter">
          <button @click="clickRem" class="picked-filter__reset-btn picked-filter__reset-btn_all">Сбросить фильтры</button>
      </span>
      <span v-for="it in needFilters" :key="it.id" class="picked-filter">
          <button class="picked-filter__reset-btn" @click="clearFiltr(it.item)">
              <i class="fas fa-times"></i>
              {{it.name}}</button>
      </span>
    </div>
</template>

<script>
import { mapGetters } from 'vuex';	
    export default {
        data() {
            return {
            }
        },
        components: {
        	
        },
        computed: {
          ...mapGetters([
            'filterItems',
            'categoryFilters'
          ]),
          needFilters() {
              let res = [];
              let pat = /f\[(\d+)\]/;
              for (const key in this.categoryFilters) {
                  let mat = key.match(pat);
                  if (mat) {
                    let el = this.filterItems.find((it) => {
                        return it.id_1s == mat[1];
                    });
                    if (el) {
                        let val = this.categoryFilters[key].split('-');
                        if (el.filter_type == 'Число') {
                            if(val[0] || val[1]) {
                                res.push({
                                    item: el,
                                    id: el.id,
                                    name: el.name + ':' + (val[0] !== '' ? ' от ' + val[0] : '') + 
                                        (val[1] !== '' ? ' до ' + val[1] : '')
                                });
                            }
                        } else {
                            if(val.length > 0) {
                                res.push({
                                    item: el,
                                    id: el.id,
                                    name: el.name + ': ' + (val.length == 1 ? el.grp_data.items[val[0]] : ''+ val.length + ' знач.')
                                });
                            }
                        }
                    }
                  }
              }
              return res;
          }
        },
        methods: {
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
            }
          },
          clearFiltr(el) {
            if (this.$router.currentRoute) {
              let obj = {};
              Object.assign(obj, this.categoryFilters);
              delete obj['f['+el.id_1s+']'];
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
  .top-filters__picked {
    display: flex;
    flex-wrap: wrap;
    width: calc(100% - 60px);
    margin-top: 10px;
  }
  .top-filters__picked:empty {
    display: none;
  }
  .top-filters__picked .picked-filter {
    margin: 0 10px 10px 0;
    display: block;
  }
  .top-filters__picked .picked-filter__reset-btn {
    background: #fc8507;
    font-size: 12px;
    border-radius: 100px;
    height: 28px;
    color: #fff;
    line-height: 24px;
    padding: 0 8px;
    border: 1px solid #fc8507;
    cursor: pointer;
  }
  .top-filters__picked .picked-filter__reset-btn_all {
    background: #fff;
    border: 1px solid #8c8c8c;
    color: #333;
  }
  .top-filters__picked .picked-filter__reset-btn i {
    float: right;
    margin-left: 4px;
    line-height: 24px;
  }
</style>