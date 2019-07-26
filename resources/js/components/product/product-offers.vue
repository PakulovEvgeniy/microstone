<template>
    <div v-if="filterItemsDef.length" class="products-page__offers" >
      <div class="offer-representation-in-category" :style="styleForHeight">
        <div class="offers-block">
          <div ref="inn" class="inner-block">
            <show-switch type="show-more" :visible="isCollapse===true" @clickSwitch="onSwitch(false)"></show-switch>
            <item-block v-if="curItem" :name="curItem.name"></item-block>
            <item-block v-for="it in items" :name="it.name" :key="it.id" @clickOffer="onClickOffer(it)"></item-block>
           
            <show-switch type="hide-all" :visible="isCollapse===false" @clickSwitch="onSwitch(true)"></show-switch>
          </div>
        </div>
      </div>
    </div>
</template>

<script>
  import { mapGetters } from 'vuex';
  import showSwitch from '../system/show-switch.vue';
  import itemBlock from '../system/item-block.vue';
    export default {
        data() {
            return {
              isCollapse: null
            }
        },
        components: {
          'show-switch': showSwitch,
          'item-block': itemBlock
        },
        computed: {
          ...mapGetters([
            'screenWidth',
            'filterItemsDef',
            'filterItems',
            'categoryFilters'
          ]),
          styleForHeight() {
            let ht = this.isCollapse===false ? 'auto' : '42px';
            return {'height' : ht}
          },
          curItem() {
            let obj = {};
            let pat = /f\[(\d+)\]/;
            let fCount = 0;
            for (let key in this.categoryFilters) {
              if (key.match(pat)) {
                obj[key] = this.categoryFilters[key];
                fCount++;
              }
            }
            if (fCount == 0) {return undefined;}
            let res = this.filterItemsDef.find((elFDef) => {
              let obj2 = {};
              let f2Count = 0;
              elFDef.params.forEach((item) => {
                let el = this.filterItems.find((elem) => {
                  return elem.id_1s == item.filters_id_1s;
                });
                if (el) {
                  let arr = item.value.split('#-#');
                  if (el.filter_type=="Число") {
                    obj2['f['+el.id_1s+']'] = ''+(!arr[0] ? el.grp_data.min : arr[0])+'-'
                        +(!arr[1] ? el.grp_data.max : arr[1]);
                    f2Count++;
                    
                  } else {
                    obj2['f['+el.id_1s+']'] = arr.map((n) => {
                      return el.grp_data.items.findIndex((n1) => {
                        return n1 == n;
                      })
                    }).join('-');
                    f2Count++;
                  }
                }
              });
              if (f2Count!=fCount) return false;
              for (let key in obj) {
                if (obj[key] != obj2[key]) {
                  return false;
                }
              }
              return true;
            });
            return res;
          },
          items() {
            return this.filterItemsDef;
          }
        },
        methods: {
          onSwitch(mode) {
            this.isCollapse = mode;
          },
          onClickOffer(it) {
            if (this.$router.currentRoute) {
              let obj = {};
              Object.assign(obj, this.categoryFilters);
              let pat = /f\[(\d+)\]/;
              let arKey = [];
              for (let key in obj) {
                if (key.match(pat)) {
                  arKey.push(key);
                }
              }
              arKey.forEach((k) => {
                delete obj[k];
              })
              it.params.forEach((item) => {
                let el = this.filterItems.find((elem) => {
                  return elem.id_1s == item.filters_id_1s;
                });
                if (el) {
                  let arr = item.value.split('#-#');
                  if (el.filter_type=="Число") {
                    obj['f['+el.id_1s+']'] = ''+(!arr[0] ? el.grp_data.min : arr[0])+'-'
                        +(!arr[1] ? el.grp_data.max : arr[1]);
                    
                  } else {
                    obj['f['+el.id_1s+']'] = arr.map((n) => {
                      return el.grp_data.items.findIndex((n1) => {
                        return n1 == n;
                      })
                    }).join('-');
                  }
                }
              });
              this.$router.push({
                path: this.$router.currentRoute.path,
                query: obj
              });
            }
          }
        },
        watch: {
          screenWidth() {
            if (this.$refs.inn.offsetHeight>42) {
              if (this.isCollapse===null) {
                this.isCollapse = true;
              }
            } else {
              this.isCollapse = null;
            }
          }
        },
        mounted() {
          if (this.$refs.inn.offsetHeight>42) {
            this.isCollapse = true;
          };
        }
    }
</script>

<style>
  .products-page__offers {
    width: 100%;
    border-bottom: 1px solid #eaeaea;
  }
  .offer-representation-in-category .inner-block {
    padding: 0 20px;
  }
  @media (min-width: 992px){
    .offer-representation-in-category {
      margin: 1em 0;
      overflow: hidden;
    }
  }
  @media (max-width: 991px) {
    .products-page__offers {
        display: none;
    }
  }
</style>