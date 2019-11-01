<template>
    <ul ref="ulSub" class="catalog-subcatalog" :class="'level-'+level" @mouseenter="onMouseEnter2($event)">
      <li v-for="item in items" :key="item.id" @mouseenter="onMouseEnter(item.id)" @mouseleave="onMouseLeave(item.id)">
         <router-link :to="'/category/'+item.chpu" class="catalog-icon">
           <span><img :src="item.image" width="22" height="22"></span>
           <span class="title">{{item.name}}</span>
           <span v-if="item.childrens.length" class="icon"><i class="fa fa-chevron-right"></i></span>
         </router-link>
         <div v-if="item.childrens.length" class="sub-wrap" v-show="focusItemId == item.id" :style="{'min-height': menuHeight+'px'}">
            <subcategory  :items="item.childrens" :level="level+1" :minHeight="menuHeight" :visibl="focusItemId == item.id"></subcategory>
         </div>
      </li>
      <li :style="{'height': liSpaceHeight+'px'}" class="space"></li>
    </ul>
</template>

<script>
    export default {
        data() {
            return {
              focusItemId: 0,
              idTime:null,
              idTime3:null,
              liSpaceHeight:0,
              menuHeight: 0
            }
        },
        props: [
          'items',
          'level',
          'minHeight',
          'visibl'
        ],
        computed: {
          
        },
        methods: {
          onMouseEnter(id) {
            if (this.focusItemId == id) {
              clearTimeout(this.idTime3);
              return;
            }
            this.idTime = setTimeout(() => {
              this.focusItemId = id;
            }, 300);
          },
          onMouseLeave(id) {
            if (this.focusItemId != id) {
              clearTimeout(this.idTime);
              return;
            }
            this.idTime3 = setTimeout(() => {
              this.focusItemId = 0
            }, 300);
          },
          onMouseEnter2(e) {
            this.menuHeight = Math.max(e.target.offsetHeight, this.minHeight);
          }
        },
        watch: {
          visibl(val) {
            if (val) {
              this.liSpaceHeight = Math.max(0, this.minHeight - this.$refs['ulSub'].offsetHeight-3);
            } else {
              this.liSpaceHeight = 0;
            }
          }
        } 
    }
</script>

<style>
  .catalog-subcatalog .space {
    border: none !important;
    border-right: 1px solid #ddd !important;
  }
</style>