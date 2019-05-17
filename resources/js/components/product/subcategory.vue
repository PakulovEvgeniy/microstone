<template>
    <ul class="catalog-subcatalog" :class="'level-'+level">
      <li v-for="item in items" :key="item.id" @mouseenter="onMouseEnter(item.id)" @mouseleave="onMouseLeave(item.id)">
         <a class="catalog-icon">
           <span><img :src="item.image" width="22" height="22"></span>
           <span class="title">{{item.name}}</span>
           <span v-if="item.childrens.length" class="icon"><i class="fa fa-chevron-right"></i></span>
         </a>
         <div v-if="item.childrens.length" class="sub-wrap" v-show="focusItems[item.id]">
            <subcategory  :items="item.childrens" :level="level+1"></subcategory>
         </div>
      </li>
    </ul>
</template>

<script>
    export default {
        data() {
            return {
              focusItems: {},
              idTime:null
            }
        },
        props: [
          'items',
          'level'
        ],
        computed: {
          
        },
        methods: {
          onMouseEnter(id) {
            this.idTime = setTimeout(() => {
              this.$set(this.focusItems,id,true);
            }, 300);
          },
          onMouseLeave(id) {
            if (!this.focusItems[id]) {
              clearTimeout(this.idTime);
              return;
            }
            setTimeout(() => {
              this.focusItems[id] = false
            }, 300);
          }
        }
    }
</script>

<style>
  
</style>