<template>
    <div class="category-menu">
      <ul class="catalog">
        <li v-for="item of catalog" :key="item.id">
          <a class="catalog-icon"><span>{{item.name}}</span></a>
          <div v-if="item.childrens.length" class="sub-wrap">
            <ul class="catalog-subcatalog level-1">
              <li v-for="item2 of item.childrens" :key="item2.id">
                
              </li>
            </ul>
          </div>
        </li>
      </ul>
    </div>
</template>

<script>
import { mapGetters } from 'vuex';
import Subcategory from './subcategory.vue';
    export default {
        data() {
            return {
            }
        },
        computed: {
          ...mapGetters([
                'getCatalog'
          ]),
          catalog () {
            return this.getCatalog.items
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
    width: 220px;
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
  }
  .catalog li a {
    color: #333;
    display: flex;
    align-items: center;
    text-decoration: none;
  }
  .catalog>li>a {
    min-height: 40px;
  }
  .catalog>li:last-child {
    box-shadow: inset 0 -1px 0 0 #ddd;
    border-radius: 0 0 3px 3px !important;
    border-bottom: none;
  }
  .catalog:hover>li>a::before {
    box-shadow: inset -12px 0 24px -14px #ddd;
    content: ' ';
    position: absolute;
    top: 0;
    left: 0;
    width: 220px;
    height: 100%;
    transition: box-shadow .2s;
  }
  .catalog>li:hover>a:before {
    display:none
  }
  .catalog>li:hover {
    border-right: none;
  }
  .catalog>li:hover>a {
    color: rgb(29, 113, 184);
  }
  .catalog-icon {
    position: relative;
  }
</style>