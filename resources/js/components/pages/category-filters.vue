<template>
    <div class="all-filters" v-title="itemHeader">
      <not-found-comp v-if="!itemCur"></not-found-comp>
      <template v-else>
        <bread-crump v-show="getScreenState>1" :links="breadItems"></bread-crump>
        <h1>
          <router-link class="extended-filters-to-category" :to="filtrPath">
              <i class="fa fa-reply"></i>
              Вернуться в каталог
          </router-link>
          Все фильтры
        </h1>
        <div class="extended-filters-wrap">
            <div class="catalog-filters">
                <div class="filter-list">
                    <div class="col">
                        <filter-component v-for="el in filterItems1" :key="el.id"
                          :item="el" ></filter-component>
                    </div>
                    <div class="col" v-show="getScreenState > 1">
                        <filter-component v-for="el in filterItems2" :key="el.id"
                          :item="el" ></filter-component>
                    </div>
                    <div class="col" v-show="getScreenState > 1">
                        <filter-component v-for="el in filterItems3" :key="el.id"
                          :item="el" ></filter-component>
                    </div>
                    <div class="col" v-show="getScreenState > 1">
                        <filter-component v-for="el in filterItems4" :key="el.id"
                          :item="el" ></filter-component>
                    </div>
                </div>
                <div class="desktop-filter-buttons">
                  <div class="description">
                      Выберите интересующие параметры и нажмите "Показать"
                  </div>
                  <button class="button-ui button-ui_brand" @click="clickUse">Показать</button>
                  <button class="button-ui button-ui_white" @click="clickRem">Сбросить фильтры</button>
                </div>
            </div>
        </div>
        <div class="filters-seo-text-no-spoiler">
            {{textFilters}}
        </div>
      </template>
    </div>
</template>

<script>
    import { mapGetters } from 'vuex';
    import Breadcrump from '../system/breadcrump.vue';
    import filterComp from '../system/filter-comp.vue';
    import notFoundComp from './general/not-found-comp.vue';
    
    export default {
        data() {
            return {
            }
        },
        computed: {
          ...mapGetters([
            'getCatalog',
            'filterItems',
            'getScreenState',
            'categoryFilters'
          ]),
          fPath() {
            return this.$router.currentRoute.path.replace('/filters','');
          },
          filtrPath() {
            return {
              path: this.fPath,
              query: this.categoryFilters
            }
          },
          filterItems1 () {
            if (this.getScreenState<2) {
                return this.filterItems
            }
            return this.filterItems.filter((el, ind) => {
                  return ind % 4 == 0
            })
          },
          filterItems2 () {
              return this.filterItems.filter((el, ind) => {
                  return ind % 4 == 1
              })
          },
          filterItems3 () {
              return this.filterItems.filter((el, ind) => {
                  return ind % 4 == 2
              })
          },
          filterItems4 () {
              return this.filterItems.filter((el, ind) => {
                  return ind % 4 == 3
              })
          },
          itemCur() {
            if (this.$route.params.idF) {
                let it = this.findItem(this.getCatalog.items, this.$route.params.idF);
                return it; 
             } else {
               return this.getCatalog.items;
            }
          },
          itemHeader() {
            return  this.itemCur ? (this.itemCur.name ? 'Подобрать ' + this.itemCur.name + ' по параметрам' : 'Подобрать по параметрам') : 'Страница не найдена';
          },
          textFilters() {
            return  this.itemCur.name ? 'Подобрать ' + this.itemCur.name + ' по параметрам. Каталог с условиями подбора по цене, цвету и другим характеристикам' : '';
          },
          breadItems() {
            let arr = [];
            if (this.itemCur != this.getCatalog.items) {
                let it = this.itemCur;
                arr.unshift({
                    id: it.id,
                    name: it.name,
                    link: '/category/'+it.chpu
                });
                while (it.parent_id) {
                    it = this.findItemById(this.getCatalog.items, it.parent_id);
                    arr.unshift({
                        id: it.id,
                        name: it.name,
                        link: '/category/'+it.chpu
                    });
                }
            }
            arr.unshift({
                id: 0,
                name: 'Каталог',
                link: '/category'
            })
            return arr;
          }
        },
        methods: {
           findItem(items, id) {
                for (let i = 0; i<items.length ; i++) {
                    let it = items[i];
                    if (it.chpu == id) {
                        return it;
                    }
                    if (it.childrens.length) {
                        let itm = this.findItem(it.childrens, id);
                        if (itm) {return itm}
                    }
                }
            },
            findItemById(items, id) {
                for (let i = 0; i<items.length ; i++) {
                    let it = items[i];
                    if (it.id_1s == id) {
                        return it;
                    }
                    if (it.childrens.length) {
                        let itm = this.findItemById(it.childrens, id);
                        if (itm) {return itm}
                    }
                }
            },
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
                path: this.fPath,
                query: obj
              });
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
                  path: this.fPath,
                  query: obj
              });
            }
          }           
        }, 
        components: {
            'bread-crump': Breadcrump,
            'filter-component': filterComp,
            notFoundComp
        },
        beforeRouteEnter (to, from, next) {
          next(vm => {
            vm.$store.commit('setNonVisibleAside', true);
          })
        }
    }
</script>

<style>
  .extended-filters-wrap {
    border-radius: 4px;
    box-shadow: inset 0 -1px 0 0 rgba(0,0,0,0.1);
    width: 100%;
    display: inline-block;
    border: 1px solid #ddd;
    position: relative;
    background-color: #fff;
    margin-bottom: 30px;
    padding: 15px 14px;
  }
  .extended-filters-wrap .filter-list {
      display: flex;
  }
  .extended-filters-wrap .col {
      flex-grow: 1;
      margin-right: 2%;
      width: 23.5%;
  }
  .extended-filters-wrap .col:last-child {
      margin-right: 0;
  }
  .desktop-filter-buttons {
    padding-top: 25px;
  }
  .desktop-filter-buttons button {
    line-height: 30px;
    width: 200px;
    margin-right: 20px;
  }
  .desktop-filter-buttons .description {
    height: 50px;
    line-height: 50px;
  }
  .extended-filters-to-category {
    font-weight: normal;
    color: #0094d9;
    border: none;
    display: inline-block;
    float: right;
    font-size: 16px;
    line-height: 40px;
    }
  .all-filters h1 {
    font-size: 28px;
    margin-top: 22px;
    margin-bottom: 11px;
  }
  .filters-seo-text-no-spoiler {
    margin-bottom: 50px;
    color: #727272;
    font-size: 13px;
    max-height: 100px;
   }
   @media (max-width: 991px) {
     .extended-filters-wrap .col {
        margin-right: 0;
        width: 100%;
     }
     .desktop-filter-buttons .description {
       display: none;
     }
     .desktop-filter-buttons {
        border-top: 1px solid #ddd;
        display: block;
        position: fixed;
        bottom: 0;
        left: 0;
        background: #fff;
        width: 100%;
        padding: 16px 10px;
     }
    .desktop-filter-buttons button {
        width: 49%;
        margin: 0;
        float: right;
    }
    .desktop-filter-buttons button+button {
        margin-right: 2%;
        float: left;
    }
   } 
</style>