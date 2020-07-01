<template>
    <div v-title="title">
      <nav class="main-page-panel-link">
        <router-link to="/category">
          <i class="fa fa-shopping-bag bag"></i>
          <span>Каталог</span>
          <i class="fa fa-chevron-right arrow"></i>
        </router-link>
      </nav>
      <banner :bannerList="banners"></banner>
      <div class="good-list">
        <h2>Популярные товары и категории</h2>
          <div class="good-category">
            <router-link v-for="it in goodCategory" :to="'/category/'+it.chpu" :key="it.id">
              <div class="image">
                <picture><img width="80px" height="80px" :src="it.image"></picture>
              </div>
              <div class="caption" v-html="it.name"></div>
            </router-link>
          </div>
          <div class="good-products">
            <router-link v-for="it in goodProduct" :to="'/product/'+it.chpu" :key="it.id">
              <div class="image">
                <picture><img width="80px" height="80px" :src="it.image"></picture>
              </div>
              <div class="good-mnf">{{it.manuf.join(', ')}}</div>
              <div class="caption" v-html="it.name"></div>
              <div class="good-price"><span>{{it.min_price!=it.max_price ? ''+(+it.min_price).toFixed(2)+' - '+(+it.max_price).toFixed(2) : (+it.min_price).toFixed(2)}}</span> руб.</div>
            </router-link>
        </div>
      </div>
    </div>
</template>

<script>
    import { mapGetters } from 'vuex';
    import Banner from '../system/banner.vue';
    export default {
        data() {
            return {
            }
        },
        computed: {
          ...mapGetters([
            'banners',
            'popularProducts',
            'settings'
          ]),
          title() {
            return this.settings.company_name;
          },
          goodCategory() {
            return this.popularProducts.category.slice(0,4);
          },
          goodProduct() {
            return this.popularProducts.product;
          }
        },
        methods: {
            
        },
        components: {
            'banner': Banner
        },
        beforeRouteEnter (to, from, next) {
          next(vm => {
            vm.$store.commit('setNonVisibleAside', false);
          })
        }
    }
</script>

<style lang="less">
  @import '../../../less/smart-grid.less';
    .good-list {
      h2 {
        font-size: 20px;
        font-weight: bold;
        margin-bottom: 10px;
      }
    }
    .good-category, .good-products {
      .row-flex();
    }
    .good-category a, .good-products a {
      .col();
      .size(6);
      .size-sm(12);
      background: white;
      border-radius: 4px;
      border: 1px solid #ddd;
      padding: 15px;
      margin-bottom: 10px;
    }
    .good-category .image, .good-products .image {
      width: 100%;
      text-align: center;
      height: 120px;
      line-height: 120px;
      margin-bottom: 20px;
    }
    .good-category {
      .caption {
        font-size: 16px;
        text-align: center;
        height: 75px;
        color: #0094d9;
        overflow: hidden;
        font-weight: bold;
        .lg(font-size, 14px);
        .md(font-size, 16px);
      }
      .image {
        .from-to(@break_md + 1px, @break_lg, {
          height: 100px;
          line-height: 100px;
        });
      }
      a:hover {
        box-shadow: 0 2px 7px 1px #babbd1;
        transition: box-shadow .2s;
        color: rgb(29, 113, 184);
      }
    }
    
    .good-products {
      .image {
        height: 90px;
        line-height: 90px;
      }
      .caption {
        font-size: 15px;
        height: 3.2em;
        color: #0094d9;
        overflow: hidden;
      }
      a:hover {
        box-shadow: 0 2px 7px 1px #babbd1;
        transition: box-shadow .2s;
        text-decoration: none;
        color: #333;
        .caption {
          color: tomato;
        }
      }
      span {
        font-weight: bold;
      }
      h2 {
        font-size: 18px;
      }
    }

    .good-mnf {
      font-size: 11px;
      color: #333;
    }
    .main-page-panel-link {
      position: relative;
      display: none;
      margin-left: -15px;
      margin-right: -15px;
      .md-block({
        height: 90px;
        display: block;
      });
      &:before {
        background: rgba(29, 113, 184,0.8);
        display: block;
        content: ' ';
        position: absolute;
        top: 0;
        left: 0;
        z-index: 0;
        width: 100%;
        height: 100%;
      }
      a {
        color: #fff;
        display: block;
        text-decoration: none !important;
        position: absolute;
        top: 0;
        line-height: 23px;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 1;
        padding-left: 65px;
        &:before {
          display: inline-block;
          content: ' ';
          height: 100%;
          vertical-align: middle;
        }
        span {
          display: inline-block;
          vertical-align: middle;
          font-size: 28px;
          font-weight: bold;
        }
        i {
          position: absolute;
          bottom: auto;
          font-size: 25px;
          top: calc(50% - 15px);
        }
        .arrow {
          right: 30px;
        }
        .bag {
          left: 30px;
        }
      }
    }
</style>