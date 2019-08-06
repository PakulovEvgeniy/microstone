<template>
    <div v-title="itemHeader">
      <bread-crump :links="breadItems"></bread-crump>
      <h1>Все фильтры</h1>
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
            'bread-crump': Breadcrump
        }
    }
</script>

<style>
    
</style>