<template>
    <div class="category-item">
      <bread-crump :links="breadItems"></bread-crump>
      <h1 v-html="itemHeader"></h1>
      <p v-html="itemDescr"></p>
      <div class="cat-items" v-if="itemChilds.length">
          <router-link :to="'/category/'+it.chpu" v-for="it in itemChilds" :key="it.id">
              <div class="image">
                  <picture><img width="80px" height="80px" :src="it.image2"></picture>
              </div>
              <div class="caption" v-html="it.name"></div>
          </router-link>
      </div>
    </div>
</template>

<script>
    import { mapGetters } from 'vuex';
    import Breadcrump from '../system/breadcrump.vue';
    export default {
        data() {
            return {
            }
        },
        computed: {
          ...mapGetters([
            'getCatalog'
          ]),
          itemCur() {
            if (this.$route.params.id) {
                let it = this.findItem(this.getCatalog.items, this.$route.params.id);
                return it || this.getCatalog.items; 
             } else {
               return this.getCatalog.items;
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
            }
        }, 
        components: {
            'bread-crump': Breadcrump
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
    .cat-items {
        display: flex;
        flex-wrap: wrap;
    }
    .cat-items a:hover {
        color: rgb(29, 113, 184);
    }
    .cat-items a {
        background: white;
        border-radius: 4px;
        box-shadow: inset 0 -1px 0 0 rgba(0,0,0,0.1);
        border: 1px solid #ddd;
        width: 220px;
        padding: 15px;
        margin-right: 10px;
        margin-bottom: 10px;
    }
    .cat-items .image {
        width: 100%;
        text-align: center;
        height: 120px;
        line-height: 120px;
        margin-bottom: 20px;
    }
    .cat-items .caption {
        font-size: 16px;
        text-align: center;
        height: 75px;
        color: #0094d9;
        overflow: hidden;
        font-weight: bold;
    }
    .cat-items img {
        height: 80px;
        width: 80px;
    }
</style>