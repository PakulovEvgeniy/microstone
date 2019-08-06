<template>
    <div v-title="itemHeader">
      <bread-crump :links="breadItems"></bread-crump>
      <h1>Все фильтры</h1>
      <div class="extended-filters-wrap">
          <div class="catalog-filters">
              <div class="filter-list">
                  <div class="col">
                      <filter-component v-for="el in filterItems1" :key="el.id"
                        :item="el" ></filter-component>
                  </div>
                  <div class="col">
                      <filter-component v-for="el in filterItems2" :key="el.id"
                        :item="el" ></filter-component>
                  </div>
                  <div class="col">
                      <filter-component v-for="el in filterItems3" :key="el.id"
                        :item="el" ></filter-component>
                  </div>
                  <div class="col">
                      <filter-component v-for="el in filterItems4" :key="el.id"
                        :item="el" ></filter-component>
                  </div>
              </div>
              <div class="desktop-filter-buttons"></div>
          </div>
      </div>
    </div>
</template>

<script>
    import { mapGetters } from 'vuex';
    import Breadcrump from '../system/breadcrump.vue';
    import filterComp from '../system/filter-comp.vue';
    
    export default {
        data() {
            return {
            }
        },
        computed: {
          ...mapGetters([
            'getCatalog',
            'filterItems'
          ]),
          filterItems1 () {
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
                return it || this.getCatalog.items; 
             } else {
               return this.getCatalog.items;
            }
          },
          itemHeader() {
            return 'Подобрать ' + this.itemCur.name + ' по параметрам' || 'Подобрать по параметрам';
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
            } 
        }, 
        components: {
            'bread-crump': Breadcrump,
            'filter-component': filterComp
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
</style>