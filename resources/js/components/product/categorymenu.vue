<template>
    <div class="category-menu">
      <ul class="catalog" @mouseenter="onMouseEnter2($event)" @mouseleave="onMouseLeave2">
        <li v-for="item of catalog" :key="item.id" @mouseenter="onMouseEnter(item.id)" @mouseleave="onMouseLeave(item.id)">
          <a class="catalog-icon">
            <span><img width="22px" height="22px" :src="item.image"></span>
            <span class="title">{{item.name}}</span>
            <span class="icon"><i class="fa fa-chevron-right"></i></span>
          </a>
          <div v-if="item.childrens.length" class="sub-wrap" v-show="focusItems[item.id]" :style="{'min-height': menuHeight+'px'}">
            <subcategory :items="item.childrens" :level="1"></subcategory>
          </div>
        </li>
      </ul>
      <div v-if="visBacdrop" class="modal-backdrop"></div>
    </div>
</template>

<script>
import { mapGetters } from 'vuex';
    export default {
        data() {
            return {
              focusItems: {},
              idTime: null,
              idTime2: null,
              visBacdrop: false,
              menuHeight: 0
            }
        },
        computed: {
          ...mapGetters([
                'getCatalog'
          ]),
          catalog () {
            return this.getCatalog.items
          }
        },
        methods: {
          onMouseEnter(id) {
            this.idTime = setTimeout(() => {
              this.$set(this.focusItems,id,true);
            }, 300);
          },
          onMouseEnter2(e) {
            this.menuHeight = e.target.offsetHeight;
            this.idTime2 = setTimeout(() => {
              this.visBacdrop = true;
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
          },
          onMouseLeave2() {
            if (!this.visBacdrop) {
              clearTimeout(this.idTime2);
              return;
            }
            setTimeout(() => {
              this.visBacdrop = false
            }, 300);
          }
        }
    }
</script>

<style>
  .catalog {
    position: relative;
    margin-left: -1px;
    margin-bottom: 30px;
    height: auto;
    width: 240px;
    font-size: 14px;
    z-index: 898;
  }

  .catalog li {
    display: block;
    border: 1px solid #ddd;
    border-left: 2px solid #80cafc;
    border-bottom: none;
    padding: 0;
    background: #fff;
    font-size: 13px;
  }
  .catalog li a {
    color: #333;
    display: flex;
    align-items: center;
    text-decoration: none;
    min-height: 40px;
  }
  .catalog li>a {
    min-height: 40px;
  }
  .catalog>li:last-child {
    box-shadow: inset 0 -1px 0 0 #ddd;
    border-radius: 0 0 3px 3px !important;
    border-bottom: none;
  }
  .catalog:hover li>a::before {
    box-shadow: inset -12px 0 24px -14px #ddd;
    content: ' ';
    position: absolute;
    top: 0;
    left: 0;
    width: 240px;
    height: 100%;
    transition: box-shadow .2s;
  }
  .catalog li:hover>a:before {
    display:none
  }
  .catalog li:hover {
    border-right: none;
  }
  .catalog li:hover>a {
    color: rgb(29, 113, 184);
  }
  .catalog-icon {
    position: relative;
  }
  .catalog li>.sub-wrap {
    -moz-border-radius-topleft: 0;
    -webkit-border-top-left-radius: 0;
    border-top-left-radius: 0;
    -moz-border-radius-bottomleft: 0;
    -webkit-border-bottom-left-radius: 0;
    border-bottom-left-radius: 0;
    -moz-border-radius-topright: 4px;
    -webkit-border-top-right-radius: 4px;
    border-top-right-radius: 4px;
    -moz-border-radius-bottomright: 4px;
    -webkit-border-bottom-right-radius: 4px;
    border-bottom-right-radius: 4px;
    background: #fff;
    position: absolute;
    left: 240px;
    width: 240px;
    z-index: 1000;
    border: 1px solid #ddd;
    border-left: none;
    top: 0;
  }

 
  .catalog li>.sub-wrap .catalog-subcatalog {
    display: block;
    padding-left: 0;
    width: auto;
    position: relative;
  }
  
  .catalog li>.sub-wrap .catalog-subcatalog>li>.item-wrap {
    color: #777;
    cursor: pointer;
  }
  .catalog li>.sub-wrap .catalog-subcatalog>li {
    border-radius: 0;
    border: none;
    border-bottom: 1px solid #ddd; 
  }
  
  .catalog li>.sub-wrap .catalog-subcatalog>li>.item-wrap a {
    color: #333;
    display: inline;
    padding-right: 5px;
  }
  
  li:hover>a>.icon {
    display: none;
  }
  .catalog-icon img {
    margin-left: 9px;
    margin-right: 9px;
    width: 22px;
    height: 22px;
  }
  .catalog-icon .title {
    width: 180px;
  }
  .catalog-icon .icon {
    color: #aaa;
    font-size: 10px;
    margin-right: 10px;
  }
  .modal-backdrop {
    bottom: 0;
    left: 0;
    position: fixed;
    right: 0;
    top: 0;
    z-index: 897;
    opacity: 0.5;
    background-color: #000;
  }
  .catalog-subcatalog.level-1 .sub-wrap {
    margin-top: -1px;
  }
</style>