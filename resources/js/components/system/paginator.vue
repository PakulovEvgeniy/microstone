<template>
    <div class="pagination-container">
      <div class="pagination-widget">
        <ul class="pagination-widget__pages">
          <li class="pagination-widget__page">
            <a 
              @click="onClick(1)" 
              class="pagination-widget__page-link"
              :class="{'pagination-widget__page-link_disabled' : 1==curPage}"
            >
              <i class="fa fa-angle-double-left"></i>
            </a>
          </li>
          <li class="pagination-widget__page">
            <a 
              @click="onClick(prevPage)" 
              class="pagination-widget__page-link"
              :class="{'pagination-widget__page-link_disabled' : prevPage==curPage}"
            >
              <i class="fa fa-angle-left"></i>
            </a>
          </li>
          <li class="pagination-widget__page" v-for="it in pages" :key="it" 
            :class="{'pagination-widget__page_active': it == curPage}"
          >
            <a class="pagination-widget__page-link" @click="onClick(it)">{{it}}</a>
          </li>
          <li class="pagination-widget__page">
            <a @click="onClick(nextPage)" 
              class="pagination-widget__page-link"
              :class="{'pagination-widget__page-link_disabled' : nextPage==curPage}"
            >
              <i class="fa fa-angle-right"></i>
            </a>
          </li>
          <li class="pagination-widget__page">
            <a @click="onClick(lastPage)" 
              class="pagination-widget__page-link"
              :class="{'pagination-widget__page-link_disabled' : lastPage==curPage}"
            >
              <i class="fa fa-angle-double-right"></i>
            </a>
          </li>
        </ul>
      </div>
    </div>
</template>

<script>
 import { mapGetters } from 'vuex';
    export default {
        data() {
            return {
              
            }
        },
        props: [
          'itemQty',
          'numPage',
          'qtyOnPage'
        ],
        computed: {
          ...mapGetters([
            'categoryFilters'
          ]),
          lastPage() {
            return Math.ceil(this.itemQty/this.qtyOnPage);
          },
          prevPage() {
            let pr = this.curPage - 1;
            if (pr<1) {
              pr = 1;
            }
            return pr;
          },
          nextPage() {
            let pr = this.curPage + 1;
            if (pr>this.lastPage) {
              pr = this.lastPage;
            }
            return pr;
          },
          pages() {
            let start = this.curPage - 4;
            if (start < 1) {
              start=1;
            }
            let end = start + 7;
            if (end > this.lastPage) {
              end = this.lastPage;
              start = end - 7;
              if (start<1) {
                start = 1;
              }
            }
            let arr = [];
            for (let i = start; i <= end; i++) {
              arr.push(i);
            }
            return arr;
          },
          curPage() {
            if (this.numPage>this.lastPage) {
              return 1;
            }
            return this.numPage;
          }
        },
        methods: {
          onClick(num) {
            if (num==this.curPage) {
              return;
            }
            this.$emit('changePage', num);
          }
        }
    }
</script>

<style>
  .pagination-widget__pages {
    display: flex;
    justify-content: center;
    border-radius: 8px;
    background-color: #fff;
    box-sizing: unset;
    box-shadow: 0 1px 2px 0 rgba(0,0,0,0.2);
    border: solid 1px #f2f2f2;
    height: 48px;
    font-size: 16px;
    list-style: none;
    padding: 0;
    text-align: center;
  }
  .pagination-widget__page {
    display: block;
    width: 65px;
    height: 49px;
    box-sizing: border-box;
  }
  .pagination-widget__page-link {
    display: block;
    padding-top: 11px;
    border: solid 3px transparent;
    color: #8c8c8c;
    text-decoration: none !important;
    font-weight: bold;
    font-size: 16px;
    height: 100%;
    box-sizing: border-box;
  }
  .pagination-widget__page-link:hover {
    background-color: #E8F2FB;
    border: solid 3px #E8F2FB;
    color: #1D71B8;
    padding-top: 11px;
  }
  .pagination-widget__page_active {
    border: #fff;
    border-bottom: solid 3px #1D71B8;
    color: #1D71B8;
  }
  .pagination-widget__page_active a {
    cursor: not-allowed;
  }
  .pagination-widget__page_active a:hover {
    background-color: #fff;
    border-color: #fff;
  }
  .pagination-widget__page-link_disabled {
    cursor: not-allowed;
    color: #777;
  }
  .pagination-widget__page-link_disabled:hover {
    background-color: #fff;
    border-color: #fff;
    color: #777;
  }
</style>