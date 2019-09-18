<template>
    <div class="page-products">
      <div class="products-page__mobile-search">
         <quick-seach @quickseach="onQuickSeach"></quick-seach>
      </div>
      <div class="products-page__top-block">
        <products-mobile-buttons 
          :curMode="curMode"
          @changeMode="changeMode"
          @click_sort="tf_showed=!tf_showed"
          @click_filtr="fl_showed=!fl_showed"
        ></products-mobile-buttons>
        <div v-if="filterItemsDef.length" class="products-page__offers" >
            <product-offers></product-offers>
        </div>
        <top-filters :curMode="curMode" :tf_showed="tf_showed"></top-filters>
      </div>
      <div class="products-page__content">
        <div class="products-page__left-block">
          <div class="data-filters-left-top">
          <div>
            <quick-seach @quickseach="onQuickSeach"></quick-seach>
          </div>
          </div>
          <left-filters :fl_showed="fl_showed" @close_filtr="fl_showed=false"></left-filters>
        </div>
        <div class="products-page__list">
          <div class="products-list">
            <div class="products-list__content">
              <div class="items-group" v-for="(el, k, ind) in pageByGroup" :key = "k">
                <span v-if="k!=='undefined'" class="items-group__title" :class="{'items-group__title_first' : ind==0}">{{k}}</span>
                <div class="catalog-items-list" :class="classMode">
                  <catalog-item v-for="it in el" :key="it.id" :item="it"></catalog-item>
                </div>
              </div>
              <paginator v-if="itemQty>0" :itemQty="itemQty" :numPage="numPage" @changePage="onChangePage($event)"></paginator>
            </div>
          </div>
          <div class="products-list__loader">
            <div class="loader-container" :class="classLoad"></div>
          </div>
        </div>
      </div>
    </div>
</template>

<script>
import productOffers from './product-offers.vue'; 
import { mapGetters } from 'vuex'; 
import topFilters from './top-filters.vue';
import catalogItem from './catalog-item.vue';
import paginator from '../system/paginator.vue';
import leftfilters from '../system/leftfilters.vue';
import quickSeach from '../system/quick-seach-input.vue';
import productsMobileButtons from './products-mb.vue';
    export default {
        data() {
            return {
              curGroup: '',
              quickValue: '',
              tf_showed: false,
              fl_showed:false
            }
        },
        components: {
          'product-offers': productOffers,
          'top-filters': topFilters,
          'catalog-item': catalogItem,
          'paginator' : paginator,
          'left-filters': leftfilters,
          'quick-seach' : quickSeach,
          'products-mobile-buttons': productsMobileButtons
        },
        computed: {
          ...mapGetters([
            'categoryFilters',
            'totalQty',
            'productsOfCategoryPage',
            'getScreenState',
            'filterItemsDef'
          ]),
          curMode() {
            return this.categoryFilters.mode == 'tile' ? 'tile' : 'simple';
          },
          classMode() {
            return 'view-'+this.curMode;
          },
          itemQty() {
            return this.totalQty;
          },
          numPage() {
            return +this.categoryFilters['page'] || 1;
          },
          pageByGroup() {
            if (!this.productsOfCategoryPage.length || this.productsOfCategoryPage[0].group === undefined) {
              return {
                'undefined': this.productsOfCategoryPage
              }
            }
            let arrGrp = {};
            let curGrp = undefined;
            for (let i = 0; i < this.productsOfCategoryPage.length; i++) {
              let el = this.productsOfCategoryPage[i];
              if (el.group != curGrp) {
                arrGrp[el.group] = [];
                curGrp = el.group;
              }
              arrGrp[el.group].push(el);
            }
            return arrGrp;
          },
          classLoad() {
            if (this.fl_showed) {
              return 'open-filters'
            }
            return 'hide';
          }
        },
        watch: {
          classLoad(p1) {
              if (p1 == 'open-filters') {
                this.$store.commit('setBodyBlocked', true);
              } else {
                this.$store.commit('setBodyBlocked', false);
              }
          }
        },
        beforeDestroy() {
          this.$store.commit('setBodyBlocked', false);
        },
        methods: {
          changeMode() {
            if (this.$router.currentRoute) {
              let obj = {};
              Object.assign(obj, this.categoryFilters);
              obj['mode'] = this.curMode == 'tile' ? 'simple' : 'tile';
              this.$router.push({
                path: this.$router.currentRoute.path,
                query: obj
              });
            }
          },
          onChangePage(num) {
            if (this.$router.currentRoute) {
              let obj = {};
              Object.assign(obj, this.categoryFilters);
              obj['page'] = num;
              //this.$store.commit('setCategoryFilters',{
              //  name: 'page',
              //  value: num
              //});
              this.$router.push({
                path: this.$router.currentRoute.path,
                query: obj
              });
              let scrTop = this.getScreenState == 1 ? 40 : 80;
              window.scrollTo({ top: scrTop, behavior: 'smooth' });
            }
          },
          onQuickSeach(event) {
            if (this.$router.currentRoute) {
              let obj = {};
              Object.assign(obj, this.categoryFilters);
              if (event.isSearch) {
                obj['q'] = event.value;
              } else {
                delete obj['q'];
              }
              this.$router.push({
                path: this.$router.currentRoute.path,
                query: obj
              });
            }
          }
        }

    }
</script>

<style>
  .products-page__top-block {
    border-radius: 8px;
    box-shadow: 0 1px 2px 0 rgba(0,0,0,0.16);
    border: solid 1px transparent;
    background: #fff;
    width: 100%;
    margin-bottom: 20px;
  }
  .products-page__content {
    display: flex;
  }
  .products-page__left-block {
    height: 100%;
    margin-right: 20px;
  }
  .products-page__list {
    max-width: 902px;
    width: 100%;
  }
  .products-list {
    position: relative;
  }

  .catalog-items-list {
    display: flex;
    flex-wrap: wrap;
  }

  .catalog-items-list .catalog-item {
    margin-bottom: 8px;
  }

  .catalog-items-list.view-simple .catalog-item {
    width: 100%;
  }
  .catalog-items-list.view-tile .catalog-item {
    width: 277px;
    margin-right: 20px;
    margin-bottom: 20px;
  }

  .catalog-items-list .catalog-item .n-catalog-product {
    border-radius: 8px;
    box-shadow: 0 1px 2px 0 rgba(0,0,0,0.16);
    border: solid 1px transparent;
    background: #fff;
    display: flex !important;
    flex-direction: column;
    justify-content: space-between;
    position: relative;
  }
  .catalog-items-list.view-tile .n-catalog-product {
    flex-direction: column;
    justify-content: start;
    height: 100%;
  }

  .catalog-items-list .catalog-item .n-catalog-product .n-catalog-product__main {
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    padding: 16px 16px 12px;
  }
  .catalog-items-list.view-simple .n-catalog-product .n-catalog-product__main {
    flex-flow: row wrap;
    justify-content: space-between;
    margin: 0;
    padding: 16px 20px 12px;
  }
  .catalog-items-list.view-tile .n-catalog-product .n-catalog-product__main {
    flex-direction: column;
  }
  .catalog-items-list.view-simple .n-catalog-product .n-catalog-product__info {
    margin-bottom: 4px;
  }
  .catalog-items-list.view-tile .n-catalog-product .n-catalog-product__info {
    margin-bottom: 12px;
  }
  .catalog-items-list .catalog-item .n-catalog-product .n-catalog-product__price {
    margin-bottom: 13px;
    height: 60px;
  }
  .catalog-items-list.view-tile .n-catalog-product .n-catalog-product__price {
    height: auto;
  }
  .catalog-items-list.view-simple .n-catalog-product .n-catalog-product__info .product-info {
    display: flex;
  }
  .catalog-items-list.view-simple .n-catalog-product .n-catalog-product__info .product-info__image {
    width: 92px;
    height: 92px;
    margin: 0 12px auto 0;
  }
  .catalog-items-list.view-tile .n-catalog-product .n-catalog-product__info .product-info__image {
    width: 200px;
    height: 200px;
    position: relative;
    margin: 16px auto;
  }
  .catalog-items-list .catalog-item .n-catalog-product .n-catalog-product__info .product-info__title {
    height: 85px;
  }
  .catalog-items-list.view-simple .n-catalog-product .n-catalog-product__info .product-info__title {
    font-size: 18px;
    max-width: 530px;
  }
  .catalog-items-list.view-tile .n-catalog-product .n-catalog-product__info .product-info__title {
    height: auto;
  }
  .catalog-items-list.view-tile .n-catalog-product .n-catalog-product__info .product-info__social {
    display: block;
  }
  .catalog-items-list .catalog-item .n-catalog-product .n-catalog-product__info .product-info__social {
    display: flex;
    justify-content: space-between;
    font-size: 14px;
  }
  .catalog-items-list.view-simple .n-catalog-product .n-catalog-product__info .product-info__social {
    position: absolute;
    bottom: 0;
    margin: 0 0 5px;
    left: 10px;
  }
  .catalog-items-list .catalog-item .n-catalog-product .n-catalog-product__info .product-info__image a {
    display: inline-block;
    text-align: center;
    width: 100%;
    height: 100%;
  }
  .catalog-items-list .catalog-item .n-catalog-product .n-catalog-product__info .product-info__image img {
    max-width: 100%;
    max-height: 100%;
    vertical-align: middle;
  }
  .catalog-items-list.view-simple .n-catalog-product .n-catalog-product__info .product-info__code {
    display: block;
    width: 90px;
    text-align: center;
    color: #626262;
  }
  .catalog-items-list.view-simple .n-catalog-product .n-catalog-product__info .product-info__code span {
    font-size: 13px;
    color: #888;
  }
  .catalog-items-list.view-simple .n-catalog-product .n-catalog-product__info .product-info__code span:first-child {
    display: none;
  }
  .catalog-items-list.view-simple .n-catalog-product .n-catalog-product__info .product-info__code span+span {
    margin-left: 0;
  }
  .catalog-items-list .catalog-item .n-catalog-product .n-catalog-product__info .product-info__title-link {
    height: 40px;
  }
  .catalog-items-list .catalog-item .n-catalog-product .n-catalog-product__info .product-info__title-link a{
    display: block;
    max-height: 46px;
    overflow: hidden;
  }
  .catalog-items-list.view-simple .catalog-item .n-catalog-product .n-catalog-product__info .product-info__title-link a{
    max-height: 50px;
  }
  .catalog-items-list.view-simple .n-catalog-product .n-catalog-product__info .product-info__title-link {
    height: 50px;
  }
  .catalog-items-list.view-simple .n-catalog-product .n-catalog-product__info .product-info__title-description {
    font-size: 14px;
    height: 38px;
    color: #4e4e4e;
    display: block;
    margin-top: 8px;
  }
  .catalog-items-list .catalog-item .n-catalog-product .n-catalog-product__price {
    margin-bottom: 13px;
    height: 60px;
  }
  .catalog-items-list.view-simple .n-catalog-product .n-catalog-product__buttons {
    width: 100%;
    margin-top: 0;
    display: flex;
    justify-content: space-between;
    margin-left: 104px;
  }
  .catalog-items-list.view-simple .product-price {
    text-align: right;
  }
  .catalog-items-list.view-simple .n-catalog-product .n-catalog-product__buttons .wishlist-btn {
    margin: 0 12px 0 auto;
  }
  .catalog-items-list.view-simple .n-catalog-product .n-catalog-product__buttons .waitlist-btn {
    margin-right: 12px;
  }
  .catalog-items-list .catalog-item .n-catalog-product .n-catalog-product__info .product-info__voblers {
    min-height: 35px;
    margin-top: 10px;
  }
  .catalog-items-list.view-simple .n-catalog-product .n-catalog-product__info .product-info__voblers {
    margin-left: 0;
    margin-top: 14px;
  }
  .catalog-items-list .catalog-item .n-catalog-product .n-catalog-product__footer {
    padding: 8px 15px;
    background: #f9f9f9;
    -moz-border-radius-bottomleft: 8px;
    -webkit-border-bottom-left-radius: 8px;
    border-bottom-left-radius: 8px;
    -moz-border-radius-bottomright: 8px;
    -webkit-border-bottom-right-radius: 8px;
    border-bottom-right-radius: 8px;
  }
  .catalog-items-list.view-simple .n-catalog-product .n-catalog-product__footer {
    min-height: 32px;
  }
  .catalog-items-list.view-simple .n-catalog-product .n-catalog-product__avails {
    display: flex;
    flex-direction: row-reverse;
    justify-content: flex-start;
  }
  .catalog-items-list.view-simple .n-catalog-product .n-catalog-product__avails {
    display: flex;
    flex-direction: row-reverse;
    justify-content: flex-start;
  }
  .catalog-items-list .catalog-item .n-catalog-product .n-catalog-product__info .product-info__stat {
    display: inline-flex;
  }
  .catalog-items-list .catalog-item .n-catalog-product .n-catalog-product__info .product-info__rating {
    display: inline-block;
    font-size: 18px;
    height: 18px;
    vertical-align: middle;
    margin: -3px auto 7px 0;
  }
  .catalog-items-list .catalog-item .n-catalog-product .n-catalog-product__info .product-info__comments-count {
    margin-left: 15px;
    padding-top: 3px;
  }
  .catalog-items-list.view-tile .n-catalog-product .n-catalog-product__info .product-info__title-description {
    display: none;
  }
  .catalog-items-list.view-tile .n-catalog-product .n-catalog-product__info .product-info__title a {
    display: block;
    margin-bottom: 7px;
  }
  .catalog-items-list.view-tile .n-catalog-product .n-catalog-product__price {
    height: auto;
  }
  .catalog-items-list.view-tile .n-catalog-product .n-catalog-product__buttons {
    display: flex;
    justify-content: space-between;
    margin-top: auto;
  }
  .catalog-items-list.view-tile .n-catalog-product__buttons button {
    margin-right: 10px;
  }
  .catalog-items-list.view-tile .n-catalog-product__buttons .primary-btn {
    flex-grow: 1;
  }
  .catalog-items-list.view-tile .n-catalog-product__buttons .primary-btn button {
    width: 100%;
  }
  .catalog-items-list.view-simple .n-catalog-product__buttons .primary-btn button {
    min-width: 138px;
  }
  .products-list .items-group__title {
    display: block;
    font-size: 24px;
    font-weight: bold;
    margin-bottom: 20px;
    margin-top: 10px;
  }
  .products-list .items-group__title_first {
     margin-top: 0px;
  }
  .products-list__loader .loader-container {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(255,255,255,0.7);
    z-index: 100;
    overflow: hidden;
  }
  .products-list__loader .open-filters.loader-container {
    padding: 0;
  }
  .products-page__offers {
    width: 100%;
    border-bottom: 1px solid #eaeaea;
  }
  @media (min-width: 992px) {
    .left-top-filters-search-input {
      display: block;
      border-radius: 8px;
      margin-bottom: 12px;
      height: 40px;
      background: #f2f2f2;
    }
    .products-page__mobile-search {
      display: none;
    }
    .products-list__loader {
      display: none;
    }
  }
  @media (min-width: 1200px) {
    .products-page__left-block {
      width: 278px;
      margin-right: 20px;
    }
    .products-page__list {
      width: calc(100% - 278px - 20px);
    }
  }
  @media (max-width: 1199px) and (min-width: 992px) {
    .products-page__left-block {
      width: 234px;
      margin-right: 12px;
    }
    .products-page__list {
      width: calc(100% - 234px - 12px);
    }
    .catalog-items-list.view-tile .catalog-item {
      width: 230px;
      margin-right: 12px;
      margin-bottom: 12px;
    }
    .catalog-items-list.view-simple .n-catalog-product .product-info__title {
      width: 400px;
    }
  }
  @media (max-width: 991px) {
    .products-page__left-block {
      width: 0;
      margin: 0;
    }
    .products-page__list {
      max-width: 100%;
    }
    .catalog-items-list.view-simple .n-catalog-product .n-catalog-product__info .product-info__title-description {
      height: auto;
    }
    .data-filters-left-top {
      display: none;
    }
    .products-page__mobile-search {
      display: block;
      border-radius: 8px;
      margin-bottom: 12px;
      height: 48px;
      background: #f2f2f2;
    }
    .products-page__offers {
        display: none;
    }
  }
  @media (min-width: 768px) {
    .catalog-items-list.view-tile .catalog-item:nth-of-type(3n) {
      margin-right: 0;
    }
    .catalog-items-list.view-tile .n-catalog-product .n-catalog-product__info .product-info__code {
      display: none;
    }
  }
  @media (max-width: 991px) and (min-width: 768px) {
    .catalog-items-list.view-tile .catalog-item {
      width: calc((100% - 40px) / 3);
      margin-right: 20px;
    }
    .catalog-items-list.view-simple .n-catalog-product .n-catalog-product__info .product-info__title {
      width: 400px;
    }
    .products-list__loader .loader-container {
      position: fixed;
      width: calc(100% + 40px);
      z-index: 1091;
      background: rgba(0,0,0,0.7);
    }
  }
  @media (max-width: 767px) {
    .catalog-items-list.view-tile .catalog-item {
      width: calc((100% - 8px) / 2);
      margin-right: 8px;
      margin-bottom: 8px;
    }
    .catalog-items-list.view-tile .catalog-item:nth-of-type(2n) {
      margin-right: 0;
    }
    .catalog-items-list.view-tile .n-catalog-product .n-catalog-product__info .product-info__code {
      display: none;
    }
    .catalog-items-list.view-tile .n-catalog-product .n-catalog-product__info .product-info__image {
      width: 120px;
      height: 120px;
      margin-top: 25px;
      margin-bottom: 10px;
    }
    .catalog-items-list.view-simple .n-catalog-product .n-catalog-product__info .product-info {
      flex-direction: column;
    }
    .catalog-items-list.view-simple .n-catalog-product .n-catalog-product__info {
      height: auto;
    }
    .catalog-items-list.view-simple .n-catalog-product .n-catalog-product__price {
      position: absolute;
      top: 10px;
      right: 10px;
    }
    .catalog-items-list.view-simple .n-catalog-product .n-catalog-product__info .product-info__social {
      margin-top: 0;
      position: relative;
    }
    .catalog-items-list.view-simple .n-catalog-product .n-catalog-product__info .product-info__code {
      display: none;
    }
    .catalog-items-list.view-simple .n-catalog-product .n-catalog-product__info .product-info__voblers {
      min-height: auto;
      margin: 10px 0 0;
    }
    .catalog-items-list.view-simple .n-catalog-product .n-catalog-product__info .product-info__title {
      width: auto;
      height: auto;
    }
    .catalog-items-list.view-simple .n-catalog-product .n-catalog-product__buttons {
      flex-wrap: wrap;
      margin-left: 0;
    }
    .catalog-items-list.view-simple .n-catalog-product .n-catalog-product__buttons .primary-btn {
      flex-grow: 1;
    }
    .catalog-items-list.view-simple .n-catalog-product .n-catalog-product__buttons .primary-btn button {
      width: 100%;
    }
    .catalog-items-list.view-simple .n-catalog-product .n-catalog-product__avails {
      display: block;
    }
    .products-list__loader .loader-container {
      position: fixed;
      width: 100%;
      left: 0;
      background: rgba(0,0,0,0.7);
    }
  }
</style>