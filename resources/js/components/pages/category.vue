<template>
    <div class="category-item"  v-title="itemHeader">
      <not-found-comp v-if="!itemCur"></not-found-comp>
      <template v-else>
        <page-top-back v-if="!itemChilds.length" :link="parLink" :name="parName"></page-top-back>
        <bread-crump :links="breadItems" :class="{'no-category': !itemChilds.length}"></bread-crump>
        <h1 v-html="itemHeader"></h1>
        <div v-show="itemDescr" class="item-descr" v-html="itemDescr"></div>
        <div class="cat-items" v-if="itemChilds.length">
            <div class="category-items-desktop">
                <router-link :to="'/category/'+it.chpu" v-for="it in itemChilds" :key="it.id">
                    <div class="image">
                        <picture><img width="80px" height="80px" :src="it.image2"></picture>
                    </div>
                    <div class="caption" v-html="it.name"></div>
                </router-link>
            </div>
            <div class="category-items-phone">
                <category-item-phone :link="parLink" :back="true" :name="parName">
                </category-item-phone>
                <category-item-phone v-for="it in itemChilds" :key="it.id" :link="'/category/'+it.chpu" :image="it.image2" :name="it.name" :right="true">
                </category-item-phone>
            </div>
        </div>
        <page-products v-else></page-products>
      </template>
    </div>
</template>

<script>
    import { mapGetters } from 'vuex';
    import Breadcrump from '../system/breadcrump.vue';
    import PageProducts from './product/pageproducts.vue';
    import pageTopBack from '../system/page-top-back.vue';
    import categoryItemPhone from './product/category-item-phone.vue';
    import notFoundComp from './general/not-found-comp.vue';
    export default {
        data() {
            return {
            }
        },
        computed: {
          ...mapGetters([
            'getCatalog',
          ]),
          itemCur() {
            if (this.$route.params.id) {
                let it = this.findItem(this.getCatalog.items, this.$route.params.id);
                return it; 
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
            return this.itemCur ? this.itemCur.name || 'Каталог товаров' : 'Страница не найдена';
          },
          itemDescr() {
            return this.itemCur ? this.itemCur.description || '' : '';
          },
          itemChilds() {
            return this.itemCur ? this.itemCur.childrens || this.itemCur : undefined;
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
            'page-products': PageProducts,
            'page-top-back': pageTopBack,
            categoryItemPhone,
            notFoundComp
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

<style lang="less">
@import '../../../less/smart-grid.less';
    .category-item {
        h1 {
            margin-top: 10px;
            padding-top: 0;
            font-size: 28px;
            margin-bottom: 11px;
        }
        p {
            font-size: 14px;
        }
        .item-descr {
            margin-bottom: 15px;
            font-size: 14px;
            p {
                margin-bottom: 5px;
                margin-top: 5px;
            }
        }
    } 
    .category-items-desktop {
        .row-flex();
        a {
            .col();
            .size(6);
            background: white;
            border-radius: 4px;
            border: 1px solid #ddd;
            padding: 15px;
            margin-bottom: 10px;
        }
        a:hover {
            box-shadow: 0 2px 7px 1px #babbd1;
            transition: box-shadow .2s;
        }
        .image {
            width: 100%;
            text-align: center;
            height: 120px;
            line-height: 120px;
            margin-bottom: 20px;
        }
        .caption {
            font-size: 16px;
            text-align: center;
            height: 75px;
            color: #0094d9;
            overflow: hidden;
            font-weight: bold;
        }
        img {
            height: 80px;
            width: 80px;
        }
        .sm(display, none);
    }

    .category-items-phone {
        display: none;
        .sm(display, block);
    }
</style>