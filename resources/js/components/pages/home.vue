<template>
    <div v-title="title">
      <nav class="main-page-panel-link">
        <router-link to="/category">
          <i class="fas fa-shopping-bag bag"></i>
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
            <div class="good-price"><span>{{it.min_price!=it.max_price ? ''+Math.round(it.min_price,2)+' - '+Math.round(it.max_price,2) : Math.round(it.min_price, 2)}}</span> руб.</div>
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
                title: 'Интернет-магазин "Микростоун"'
            }
        },
        computed: {
          ...mapGetters([
            'banners',
            'popularProducts'
          ]),
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

<style>
    .good-category, .good-products {
      display:flex;
      flex-wrap: wrap;
    }
    .good-category a, .good-products a {
      background: white;
      border-radius: 4px;
      border: 1px solid #ddd;
      width: 220px;
      padding: 15px;
      margin-right: 10px;
      margin-bottom: 10px;
    }
    .good-category .image, .good-products .image {
    width: 100%;
    text-align: center;
    height: 120px;
    line-height: 120px;
    margin-bottom: 20px;
    }
    .good-products .image {
      height: 90px;
      line-height: 90px;
    }
    .good-category .caption {
    font-size: 16px;
    text-align: center;
    height: 75px;
    color: #0094d9;
    overflow: hidden;
    font-weight: bold;
    }
    .good-products .caption {
    font-size: 15px;
    height: 3.2em;
    color: #0094d9;
    overflow: hidden;
    }
    .good-category a:hover {
      box-shadow: 0 2px 7px 1px #babbd1;
      -moz-transition: box-shadow .2s;
      -o-transition: box-shadow .2s;
      -webkit-transition: box-shadow .2s;
      transition: box-shadow .2s;
      color: rgb(29, 113, 184);
    }
    .good-products a:hover {
      box-shadow: 0 2px 7px 1px #babbd1;
      -moz-transition: box-shadow .2s;
      -o-transition: box-shadow .2s;
      -webkit-transition: box-shadow .2s;
      transition: box-shadow .2s;
      text-decoration: none;
      color: #333;
    }
    .good-products a:hover .caption {
      color: tomato;
    }
    .good-price span {
      font-weight: bold;
    }
    .good-list h2 {
      font-size: 18px;
    }
    .good-category a:nth-child(4n+4), .good-products a:nth-child(4n+4) {
      margin-right: 0;
    }
    .good-mnf {
      font-size: 11px;
      color: #333;
    }
    .main-page-panel-link {
      position: relative;
      display: none;
      margin-left: -10px;
      margin-right: -10px;
    }
    .main-page-panel-link:before {
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
    .main-page-panel-link a {
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
    }
    .main-page-panel-link a:before {
      display: inline-block;
      content: ' ';
      height: 100%;
      vertical-align: middle;
    }
    .main-page-panel-link a span {
      display: inline-block;
      vertical-align: middle;
      font-size: 28px;
      font-weight: bold;
    }
    .main-page-panel-link a i {
      position: absolute;
      bottom: auto;
      font-size: 25px;
      top: calc(50% - 15px);
    }
    .main-page-panel-link a .arrow {
      right: 30px;
    }
    .main-page-panel-link a .bag {
      left: 30px;
    }
    @media (max-width: 991px) {
      .good-list {
        display: none;
      }
      .main-page-panel-link {
        height: 90px;
        display: block;
      }
    }
    @media (max-width: 1199px) and (min-width: 992px) {
	    .good-category a, .good-products a {
        width: 169px;
        margin-right: 7px;
      }
      .good-category .image {
        height: 100px;
        line-height: 100px;
      }
      .good-category .caption {
        font-size: 14px;
      }
    }
</style>