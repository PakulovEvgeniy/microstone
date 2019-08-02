<template>
      <div class="offer-representation-in-category" :style="styleForHeight"
        :class="{'mobile-collapsed' : !mobileCollapse}"
      >
        <div class="offers-block">
          <div ref="inn" class="inner-block">
            <div class="block-heading mobile-offer-expand-container">
              <button @click="mobileCollapse = !mobileCollapse" class="button-ui button-ui_white mobile-offer-expand-container__button">
                <span><i class="fa" :class="clShevron"></i>Подборки фильтров</span>
              </button>
            </div>
            <show-switch v-if="this.getScreenState>1" type="show-more" :visible="isCollapse===true" @clickSwitch="onSwitch(false)"></show-switch>
            <item-block v-if="curItem" :name="curItem.name" :active="true" @clickOffer="onOfferClear"></item-block>
            <item-block v-for="it in items" :name="it.name" :key="it.id" @clickOffer="onClickOffer(it)"></item-block>
            
            <show-switch v-if="this.getScreenState>1" type="hide-all" :visible="isCollapse===false" @clickSwitch="onSwitch(true)"></show-switch>
            <div class="mobile-buttons collapsible">
              <button class="button-ui button-ui_white mobile-btn mobile-btn_show-more">
                Показать еще
                <i class="fa fa-chevron-down"></i>
              </button>
              <button v-if="false" class="button-ui button-ui_white mobile-btn mobile-btn_hide">
                <i class="fa fa-chevron-up"></i>
                Скрыть
              </button>
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
              isCollapse: null,
              mobileCollapse: false
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
            'categoryFilters',
            'getScreenState'
          ]),
          styleForHeight() {
            if (this.getScreenState == 1) {
              return {'height' : 'auto'}
            }
            let ht = this.isCollapse===false ? 'auto' : '42px';
            return {'height' : ht}
          },
          clShevron() {
            return this.mobileCollapse ? 'fa-chevron-up' : 'fa-chevron-down';
          },
          curItem() {
            if (this.getScreenState == 1) {
              return undefined;
            }
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
                    }).filter(e => e!=-1).join('-');
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
            if (!this.curItem) {
               return this.filterItemsDef; 
            } else {
              return this.filterItemsDef.filter((el) => {
                return el != this.curItem;
              })
            }
          }
        },
        methods: {
          onSwitch(mode) {
            this.isCollapse = mode;
          },
          onOfferClear() {
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
              this.$router.push({
                path: this.$router.currentRoute.path,
                query: obj
              });
            }
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
                      let ind = el.grp_data.items.findIndex((n1) => {
                        return n1 == n;
                      })
                      return ind;
                    }).filter(e => e!=-1).join('-');
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
            if (!this.$refs.inn) return;
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
          if (!this.$refs.inn) return;
          if (this.$refs.inn.offsetHeight>42) {
            this.isCollapse = true;
          };
        }
    }
</script>

<style>
  
  .offer-representation-in-category .inner-block {
    padding: 0 20px;
  }
  .offer-representation-in-category .mobile-offer-expand-container__button {
    width: 100%;
  }
  .offer-representation-in-category .mobile-offer-expand-container__button span {
    position: relative;
  }
  .mobile-offer-expand-container__button i {
    color: #333;
    font-size: 13px;
    margin-right: 5px;
  }
  .offer-representation-in-category .mobile-buttons {
    display: none;
    flex-wrap: wrap;
    padding: 0;
    padding-top: 10px;
    padding-bottom: 10px;
  }
  @media (min-width: 992px){
    .offer-representation-in-category {
      margin: 1em 0;
      overflow: hidden;
    }
    .offer-representation-in-category .mobile-offer-expand-container {
      display: none;
    }
  }
  @media (max-width: 991px) {
    .offer-representation-in-category {
      margin: 6px 0;
    }
    .offer-representation-in-category .mobile-offer-expand-container {
      margin-bottom: 20px;
      margin-top: 15px;
    }
    .offer-representation-in-category.mobile-collapsed .inner-block .collapsible {
      display: none;
    }
    .offer-representation-in-category .mobile-buttons {
      display: flex;
      flex-wrap: nowrap;
      min-height: 64px;
    }
    .offer-representation-in-category .mobile-buttons .mobile-btn {
      margin: 0 4px;
      padding: 0 10px;
    }
  }
</style>