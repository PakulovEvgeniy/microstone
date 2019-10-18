<template>
    <div class="product-page" v-title="title">
      <bread-crump v-show="getScreenState>1" :links="breadItems"></bread-crump>
      <div v-show="getScreenState<2" class="category-up">
          <router-link :to="breadItems[breadItems.length-2].link">
              <i class="fa fa-chevron-left"></i>
              <div class="caption" v-html="breadItems[breadItems.length-2].name"></div>
          </router-link>
      </div>
      <h1 class="price-item-title" v-html="product.name"></h1>
      <div class="price-item-code">
          Код товара:
          <span>{{product.sku}}</span>
      </div>
      <voblers></voblers>
      <div class="price-item">
          <div class="node-block">
              <div class="item-header">
                  <div ref="carus" class="col-header col-photo">
                      <div class="main-image-slider-wrap">
                          <owl-carousel :pictQty="1" :images="product.images" 
                            :width="width" type="img" 
                            :curPicture="curPicture"
                            @clickPicture="clickPict"
                            @changePict="curPicture=$event"
                          ></owl-carousel>
                          <a @click="clickPrev" class="button-left"><i class="fa fa-chevron-left"></i></a>
                          <a @click="clickNext" class="button-right"><i class="fa fa-chevron-right"></i></a>
                          <div  :style="bigPictStyle" ref="big_pict" v-show="bigPicture" class="big-picture">
                              <a @click="bigPicture=false" class="close">
                                  <i class="fa fa-times"></i>
                              </a>
                              <div v-dragscroll :style="divStyle">
                                <img :src="product.bigImages[curPicture]">
                              </div>
                          </div>
                      </div>
                      <div v-show="product.images.length>1" class="thumb-slider-wrap">
                          <owl-carousel :pictQty="4" :images="product.images2" 
                            :width="81" type="thumb" :curPicture="curPicture"
                            @changePict="curPicture=$event"
                            @clickPicture="clickPict"
                          ></owl-carousel>
                          <a @click="clickPrev" class="button-left"><i class="fa fa-chevron-left"></i></a>
                          <a @click="clickNext" class="button-right"><i class="fa fa-chevron-right"></i></a>
                      </div>
                  </div>
              </div>
          </div>
      </div>
      <over-block :active="bigPicture" @clickOver="bigPicture=false"></over-block>
    </div>
</template>

<script>
    import { mapGetters } from 'vuex';
    import Breadcrump from '../system/breadcrump.vue';
    import voblers from '../product/voblers.vue';
    import OwlCarousel from '../system/owl-carousel.vue';
    import overBlock from '../system/overblock.vue';
    import { dragscroll } from 'vue-dragscroll';
    export default {
        data() {
            return {
               curPicture: 0,
               width: 335,
               bigPicture: false,
               bigPictStyle: {
                left: 0,
                top: 0,
                'max-height': 0,
                'max-width': 0
               },
               divStyle: {
                width: 'auto',
                height: 'auto'
               }
            }
        },
        directives: {
          'dragscroll': dragscroll
        },
        computed: {
          ...mapGetters([
            'getCatalog',
            'getScreenState',
            'product',
            'screenWidth',
            'screenHeight'
          ]),
          title() {
              return this.product.name;
          },
          breadItems() {
            let arr = [];
            if (this.product.name) {
                let par = this.product.parent_id;
                arr.unshift({
                    id: this.product.id_1s,
                    name: this.product.name,
                    link: '/product/'+this.product.chpu
                });
                while (par) {
                    let it = this.findItemById(this.getCatalog.items, par);
                    arr.unshift({
                        id: it.id,
                        name: it.name,
                        link: '/category/'+it.chpu
                    });
                    par = it.parent_id
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
            },
            clickNext() {
                this.curPicture++;
                if (this.curPicture>=this.product.images.length) {
                    this.curPicture = 0;
                }
            },
            clickPrev() {
                this.curPicture--;
                if (this.curPicture<0) {
                    this.curPicture = this.product.images.length-1;
                }
            },
            clickPict(val) {
                //console.dir(this.$refs.big_pict);
                this.bigPicture = true;
                setTimeout(() => {
                    this.bigPictStyle['max-width'] = this.screenWidth+'px';
                    this.bigPictStyle['max-height'] = this.screenHeight+'px';
                    this.divStyle.width = 'auto';
                    this.divStyle.height = 'auto';
                    setTimeout(() => {
                         this.bigPictStyle.left = 'calc(50% - '+parseInt(this.$refs.big_pict.clientWidth/2) +'px)';
                         this.bigPictStyle.top =  'calc(50% - '+parseInt(this.$refs.big_pict.clientHeight/2) +'px)';
                         this.divStyle.width = this.$refs.big_pict.clientWidth + 'px';
                         this.divStyle.height = this.$refs.big_pict.clientHeight + 'px';
                    }, 0);
                }, 0);
            } 
        },
        components: {
            'bread-crump': Breadcrump,
            voblers,
            'owl-carousel': OwlCarousel,
            'over-block': overBlock
        },
        beforeRouteEnter (to, from, next) {
          next(vm => {
            vm.$store.commit('setNonVisibleAside', true);
          })
        },
        watch: {
            screenWidth(val) {
                if (this.getScreenState>1) {
                    this.width = 335;
                    return;
                }
                this.width = this.$refs.carus.clientWidth - 50;
                if (this.bigPicture) this.clickPict();
            },
            screenHeight(val) {
              if (this.bigPicture) this.clickPict();  
            }
        },
        mounted() {
           if (this.getScreenState>1) {
                this.width = 335;
                return;
           }
           this.width = this.$refs.carus.clientWidth - 50; 
        }
    }
</script>

<style>
   .price-item-title {
        font-size: 28px;
        font-weight: normal;
        margin-bottom: 11px;
    } 
    .price-item-code {
        color: #bbb;
        font-size: 1.1em;
        margin: 0 0 1em;
        display: inline-block;
        vertical-align: top;
    }
    .price-item-code + .w-product-voblers {
        margin-left: 20px;
    }
    .node-block {
        border-radius: 4px;
        box-shadow: inset 0 -1px 0 0 rgba(0,0,0,0.1);
        width: 100%;
        display: inline-block;
        border: 1px solid #ddd;
        position: relative;
        background-color: #fff;
    }
    .item-header {
        margin-left: 0;
        margin-right: 0;
        padding: 1em;
        position: relative;
    }
    .item-header .col-header.col-photo {
        position: relative;
    }
    .item-header .col-header.col-photo .main-image-slider-wrap {
        position: relative;
        padding: 0 25px;
    }
    .item-header .col-header.col-photo .thumb-slider-wrap {
        margin: 10px 0;
        width: 384px;
        position: relative;
        padding: 0 30px 0 31px;
    }
    .main-image-slider-wrap .image-slider .owl-wrapper .owl-item {
        height: 240px;
        display: table-cell;
        vertical-align: middle;
    }
    .thumb-slider-wrap .image-slider .owl-wrapper .owl-item {
        height: 50px;
        display: table-cell;
        vertical-align: middle;
    }
    .item-header .col-header.col-photo .button-left, .item-header .col-header.col-photo .button-right {
        display: inline;
        position: absolute;
        top: 0;
        bottom: 0;
        opacity: .5;
        width: 25px;
    }
    .item-header .col-header.col-photo .button-left {
        left: 7px;
    }
    .item-header .col-header.col-photo .button-right {
        right: 7px;
    }
    .item-header .col-header.col-photo .main-image-slider-wrap .button-left, .item-header .col-header.col-photo .main-image-slider-wrap .button-right {
        width: 25px;
    }
    .item-header .col-header.col-photo .button-left i, .item-header .col-header.col-photo .button-right i {
        text-align: center;
        top: 50%;
        margin-top: -11px;
        font-size: 20px;
        text-decoration: none;
        width: 100%;
        position: absolute;
        color: #b3b3b3;
    }
    .item-header .col-header.col-photo .button-left:hover, .item-header .col-header.col-photo .button-right:hover {
        background: #e6e6e6;
    }
    .item-header .col-header.col-photo .thumb-slider-wrap .owl-item .thumb {
        border-radius: 5px;
        border: 1px solid #ddd;
        display: table-cell;
        height: 54px;
        position: relative;
        text-align: center;
        vertical-align: middle;
        width: 70px;
    }
    .item-header .col-header.col-photo .thumb-slider-wrap .owl-item .thumb.active {
        border-color: #777;
    }
    .item-header .col-header.col-photo .main-image-slider-wrap .owl-item {
        text-align: center;
    }
    .item-header::after {
        content: " ";
        display: table;
        clear: both;
    }
    .category-up {
        width: calc(100% + 20px);
        margin: 0 -10px;
        background-color: #fff;
        border-bottom: 1px solid #ddd;
    }
    .category-up a {
        display: block;
        text-decoration: none !important;
        width: 100%;
        color: #333;
    }
    .category-up a i {
        font-size: 13px;
        line-height: 59px;
        display: inline-block;
        vertical-align: middle;
        padding: 0 5px 0 33px;
    }
    .category-up a .caption {
        color: #3a3a3a;
        display: inline-block;
        line-height: 60px;
    }
    .big-picture {
        padding: 10px;
        -ms-box-shadow: 0 0 60px #ccc;
        -moz-box-shadow: 0 0 60px #ccc;
        -webkit-box-shadow: 0 0 60px #ccc;
        box-shadow: 0 0 60px #ccc;
        background-color: white;
        position: fixed;
        z-index: 10000;
        display: block;
        top: 50%;
        left: 50%;
        width: auto;
        height: auto;
        max-width: 200px;
        max-height: 200px;
        overflow: hidden;
    }
    .big-picture .close {
        position: absolute;
        right: 0px;
        top: 5px;
        cursor: pointer;
        height: 20px;
        width: 20px;
        z-index: 100;
        color: #d8d8d8;
    }
    .big-picture .close:hover {
        color: gray;
    }
    .big-picture div {
        position: relative;
        overflow: hidden;
        margin-top: -10px;
        margin-left: -10px;
    }
    @media (max-width: 991px) {
        .price-item-title {
            font-size: 20px;
            margin-left: 20px;
            margin-right: 20px;
        }
        .price-item-code {
            margin-left: 20px;
            margin-right: 20px;
        }
        .item-header .col-header.col-photo .thumb-slider-wrap {
            display: none;
        }
        .item-header .col-header.col-photo {
            max-height: 250px;
            width: auto;
        }
    }
    @media (min-width: 992px) {
        .item-header .col-header {
            float: left;
        }
        .item-header .col-header.col-photo {
            margin: 0 25px 0 0;
            max-height: 330px;
            width: 385px;
        }
    }
</style>