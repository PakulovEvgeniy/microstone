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
              <paginator v-if="itemQty>0" :itemQty="itemQty" :qtyOnPage="qtyOnPage" :numPage="numPage" @changePage="onChangePage($event)"></paginator>
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
import paginator from '../../system/paginator.vue';
import leftfilters from '../../system/left-filters.vue';
import quickSeach from '../../system/quick-seach-input.vue';
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
            'filterItemsDef',
            'qtyOnPage'
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

<style lang="less">
@import '../../../../less/smart-grid.less';
  .products-page {
    &__top-block {
      border-radius: 8px;
      box-shadow: 0 1px 2px 0 rgba(0,0,0,0.16);
      border: solid 1px transparent;
      background: #fff;
      width: 100%;
      margin-bottom: 20px;
    }
    &__content {
      .u-row-flex(0);
    }
    &__left-block {
      height: 100%;
      width: 278px;
      margin-right: 20px;
      .lg-block({
        width: 234px;
        margin-right: 12px;
      });
      .data-filters-left-top {
        .md(display, none);
      }
    }
    &__list {
      width: calc(100% - 278px - 20px);
      .lg-block({
        width: calc(100% - 234px - 12px);
      });
      .md(width, 100%);
    }
    &__offers {
      width: 100%;
      border-bottom: 1px solid #eaeaea;
      .md(display, none);
    }
    &__mobile-search {
      display: none;
      .md-block({
        display: block;
        border-radius: 8px;
        margin-bottom: 12px;
        height: 48px;
        background: #f2f2f2;
      });
    }
  }

  .catalog-items-list {
    .row-flex();
    .catalog-item {
      margin-bottom: 8px;
      .col();
    }
    &.view-simple {
      .catalog-item {
        .size(24);
      }
      .n-catalog-product {
        &__main {
          flex-flow: row wrap;
          justify-content: space-between;
          margin: 0;
          padding: 16px 20px 12px;
        }
        &__price {
          .sm-block({
            position: absolute;
            top: 10px;
            right: 20px;
          });
        }
        &__info {
          margin-bottom: 4px;
          .sm(height, auto);
          .product-info {
            display: flex;
            .sm(flex-direction, column);
            &__image {
              width: 92px;
              height: 92px;
              margin: 0 12px auto 0;
            }
            &__title {
              font-size: 18px;
              max-width: 530px;
              .lg(width, 375px);
              .md(width, 400px);
              .sm-block({
                width: auto;
                height: auto;
              });
              &-description {
                font-size: 14px;
                height: 38px;
                color: #4e4e4e;
                display: block;
                margin-top: 8px;
                .md(height, auto);
              }
            }
            &__social {
              position: absolute;
              bottom: 0;
              margin: 0 0 5px;
              left: 10px;
              .sm-block({
                margin-top: 0;
                position: relative;
              });
            }
            &__code {
              display: block;
              width: 90px;
              text-align: center;
              color: #626262;
              span {
                font-size: 13px;
                color: #888;
                &:first-child {
                  display: none;
                }
                + span {
                  margin-left: 0;
                }
              }
              .sm(display, none);
            }
            &__title-link {
              height: 50px;
              a {
                max-height: 50px;
              }
            }
            &__voblers {
              margin-left: 0;
              margin-top: 14px;
              .sm-block({
                min-height: auto;
                margin: 10px 0 0;
              });
            }
          }
        }
        &__buttons {
          width: 100%;
          margin-top: 0;
          display: flex;
          justify-content: space-between;
          margin-left: 104px;
          .sm-block({
            flex-wrap: wrap;
            margin-left: 0;
          });
          .primary-btn {
            .sm(flex-grow, 1);
            button {
              min-width: 138px;
              margin-right: 0;
              .sm(width, 100%);
            }
            .buy-button {
              .sm(width, 100%);
            }
          }
        }
        &__footer {
          min-height: 32px;
        }
        &__avails {
          display: flex;
          flex-direction: row-reverse;
          justify-content: flex-start;
          .sm(display, block);
        }
      }
      .product-price {
        text-align: right;
      }
    }
    &.view-tile {
      .catalog-item {
        .size(8);
        .size-sm(12);
        .size-xs(24);
        margin-bottom: 20px;
      }
      .n-catalog-product {
        justify-content: start;
        height: 100%;
        &__info {
          margin-bottom: 12px;
          .product-info {
            &__image {
              width: 200px;
              height: 200px;
              position: relative;
              margin: 16px auto;
              .sm-block({
                width: 120px;
                height: 120px;
                margin-top: 25px;
                margin-bottom: 10px;
              });
            }
            &__title {
              height: auto;
              &-description {
                display: none;
              }
              a {
                display: block;
                margin-bottom: 7px;
              }
            }
            &__social {
              display: block;
            }
            &__code {
              display: none;
            }
          }
        }
        &__price {
          height: auto;
        }
        &__buttons {
          display: flex;
          justify-content: space-between;
          margin-top: auto;
          button {
            margin-right: 10px;
          }
          .primary-btn {
            flex-grow: 1;
            button {
              width: 100%;
            }
          }
        }
      }
    }
    .n-catalog-product {
      border-radius: 8px;
      box-shadow: 0 1px 2px 0 rgba(0,0,0,0.16);
      border: solid 1px transparent;
      background: #fff;
      display: flex !important;
      flex-direction: column;
      justify-content: space-between;
      position: relative;
      &__main {
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        padding: 16px 16px 12px;
      }
      &__price {
        margin-bottom: 13px;
        height: 60px;
      }
      &__info {
        .product-info {
          &__title {
            height: 85px;
            &-link {
              height: 40px;
              a {
                display: block;
                max-height: 46px;
                overflow: hidden;
              }
            }
          }
          &__social {
            display: flex;
            justify-content: space-between;
            font-size: 14px;
          }
          &__image {
            a {
              display: inline-block;
              text-align: center;
              width: 100%;
              height: 100%;
            }
            img {
              max-width: 100%;
              max-height: 100%;
              vertical-align: middle;
            }
          }
          &__voblers {
            min-height: 35px;
            margin-top: 10px;
          }
          &__stat {
            display: inline-flex;
          }
          &__rating {
            display: inline-block;
            font-size: 18px;
            height: 18px;
            vertical-align: middle;
            margin: -3px auto 7px 0;
          }
          &__comments-count {
            margin-left: 15px;
            padding-top: 3px;
          }
        }
      }
      &__footer {
        padding: 8px 15px;
        background: #f9f9f9;
        border-bottom-left-radius: 8px;
        border-bottom-right-radius: 8px;
      }
    }
  }

  .products-list {
    position: relative;
    .items-group__title {
      display: block;
      font-size: 24px;
      font-weight: bold;
      margin-bottom: 20px;
      margin-top: 10px;
      &_first {
        margin-top: 0px;
      }
    }
    &__loader {
      display: none;
      .md(display, block);
      .loader-container {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 100;
        overflow: hidden;
        .md-block({
          position: fixed;
          background: rgba(0,0,0,0.7);
        });
      }
      &.open-filters {
        padding: 0;
      }
    }
  }
</style>