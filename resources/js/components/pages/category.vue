<template>
    <div class="category-item"  v-title="itemHeader">
      <div class="products-page__mobile-back" v-show="!itemChilds.length">
        <i class="fa fa-chevron-left"></i>
        <router-link class="products-page__mobile-back-link" :to="parLink">{{parName}}</router-link>
      </div>
      <bread-crump v-show="getScreenState>1" :links="breadItems"></bread-crump>
      <h1 v-html="itemHeader"></h1>
      <div class="item-descr" v-html="itemDescr"></div>
      <div class="cat-items" v-if="itemChilds.length">
          <div v-show="getScreenState>1" class="category-items-desktop">
              <router-link :to="'/category/'+it.chpu" v-for="it in itemChilds" :key="it.id">
                  <div class="image">
                      <picture><img width="80px" height="80px" :src="it.image2"></picture>
                  </div>
                  <div class="caption" v-html="it.name"></div>
              </router-link>
          </div>
          <div v-show="getScreenState<2" class="category-items-phone">
              <div class="category-item-phone back">
                  <router-link :to="parLink">
                      <i class="fa fa-chevron-left"></i>
                      <div class="caption">
                          <div class="vertical-container">
                              <span>{{parName}}</span>
                          </div>
                      </div>
                  </router-link>
              </div>
              <div v-for="it in itemChilds" :key="it.id" class="category-item-phone">
                  <router-link :to="'/category/'+it.chpu">
                      <div class="image">
                          <picture><img width="35px" height="35px" :src="it.image2"></picture>
                      </div>
                      <div class="caption">
                          <div class="vertical-container">
                              <span>{{it.name}}</span>
                          </div>
                      </div>
                      <div class="category-link">
                          <i class="fa fa-chevron-right"></i>
                      </div>
                  </router-link>
              </div>
          </div>
      </div>
      <page-products v-else></page-products>
    </div>
</template>

<script>
    import { mapGetters } from 'vuex';
    import Breadcrump from '../system/breadcrump.vue';
    import PageProducts from '../product/pageproducts.vue';
    export default {
        data() {
            return {
            }
        },
        computed: {
          ...mapGetters([
            'getCatalog',
            'getScreenState'
          ]),
          itemCur() {
            if (this.$route.params.id) {
                let it = this.findItem(this.getCatalog.items, this.$route.params.id);
                return it || this.getCatalog.items; 
             } else {
               return this.getCatalog.items;
            }
          },
          parItem() {
            if (this.itemCur.parent_id) {
                return this.findItemById(this.getCatalog.items, this.itemCur.parent_id);
            }
          },
          parName() {
            if (this.parItem) {
                return this.parItem.name;
            } else if (this.itemCur.name) {
                return 'Каталог';
            } else {
                return 'Домашняя страница';
            }
          },
          parLink() {
            if (this.parItem) {
                return '/category/'+this.parItem.chpu;
            } else if (this.itemCur.name) {
                return '/category';
            } else {
                return '/';
            }
          },
          itemHeader() {
            return this.itemCur.name || 'Каталог товаров';
          },
          itemDescr() {
            return this.itemCur.description || '';
          },
          itemChilds() {
            return this.itemCur.childrens || this.itemCur;
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
            setVisibleSide(id) {
              let nonVis = false;
              if (id) {
                let it = this.findItem(this.getCatalog.items, id);
                if (it) {
                    nonVis = it.childrens.length == 0
                }

              }
              this.$store.commit('setNonVisibleAside', nonVis);
            }
        }, 
        components: {
            'bread-crump': Breadcrump,
            'page-products': PageProducts
        },
        beforeRouteEnter (to, from, next) {
          next(vm => {
            vm.setVisibleSide(vm.$route.params['id']);
          })
        },
        watch: {
            '$route' (to, from) {
              this.setVisibleSide(this.$route.params['id']);
            }
        }
    }
</script>

<style>
    .category-item h1 {
        margin-top: 0;
        padding-top: 0;
        font-size: 28px;
        margin-bottom: 11px;
    }
    .category-item p {
        font-size: 14px;
    }
    .category-items-desktop {
        display: flex;
        flex-wrap: wrap;
    }
    .category-items-desktop a:hover {
        color: rgb(29, 113, 184);
    }
    .category-items-desktop a {
        background: white;
        border-radius: 4px;
        border: 1px solid #ddd;
        width: 217px;
        padding: 15px;
        margin-right: 10px;
        margin-bottom: 10px;
    }
    .category-items-desktop a:hover {
      box-shadow: 0 2px 7px 1px #babbd1;
      -moz-transition: box-shadow .2s;
      -o-transition: box-shadow .2s;
      -webkit-transition: box-shadow .2s;
      transition: box-shadow .2s;
    }
    .category-items-desktop .image {
        width: 100%;
        text-align: center;
        height: 120px;
        line-height: 120px;
        margin-bottom: 20px;
    }
    .category-items-desktop .caption {
        font-size: 16px;
        text-align: center;
        height: 75px;
        color: #0094d9;
        overflow: hidden;
        font-weight: bold;
    }
    .category-items-desktop img {
        height: 80px;
        width: 80px;
    }
    .category-item .item-descr {
        margin-bottom: 15px;
        font-size: 14px;
    }
    .item-descr p {
        margin-bottom: 5px;
        margin-top: 5px;
    }
    .products-page__mobile-back {
        display: none;
        padding: 20px 15px;
    }
    .products-page__mobile-back i {
        font-size: 13px;
    }
    .products-page__mobile-back-link {
        text-decoration: none;
        color: #333;
        padding-left: 5px;
        margin-right: 5px;
    }
    .products-page__mobile-back-link:hover {
        color: #00608d;
        text-decoration: underline;
    }
    .category-item-phone {
        width: 100%;
        height: 95px;
        display: block;
        border-bottom: 1px solid #ddd;
        padding-left: 45px;
        background-color: white;
    }
    .category-item-phone.back {
        background-color: #f5f4f5;
    }
    .category-item-phone a {
        color: #333;
        display: table;
        height: 100%;
        text-decoration: none;
        width: 100%;
    }
    .category-item-phone i {
        display: inline-block;
        font-size: 13px;
        line-height: 60px;
        margin-left: -12px;
        text-align: center;
        width: 36px;
    }
    .category-item-phone .caption, .category-item-phone .category-link {
        display: table-cell;
        font-size: 13px;
        height: 100%;
    }
    .category-item-phone .caption2 {
        vertical-align: middle;
    }
    .category-item-phone:first-child a .caption {
        padding-left: 0;
    }
    .category-item-phone .category-link>i {
        color: #ccc;
    }
    .category-item-phone:hover, .category-item-phone:focus {
        background-color: #f6f6f6;
    }
  @media (max-width: 991px) {
    .products-page__mobile-back {
      display: block;
    }
    .category-item-phone {
        height: 60px;
        padding: 0 36px;
    }
    .category-item-phone .caption {
        font-size: 16px !important;
        line-height: 60px;
        padding: 0 13px;
        width: 100%;
    }
    .category-item-phone .caption>* {
        line-height: normal;
    }
    .category-item-phone .image {
        display: block;
        line-height: 60px;
        position: relative;
        text-align: center;
        top: 13px;
        width: 36px;
    }
  }

  @media (max-width: 991px) and (min-width: 768px) {
    .products-page__mobile-back {
      padding: 20px 0;
    }
    .category-item-phone .caption {
        padding-left: 15px;
    }
  }
</style>