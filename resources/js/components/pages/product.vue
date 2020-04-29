<template>
		<div class="product-page" v-title="title">
			<not-found-comp v-if="!product.id"></not-found-comp>
			<template v-else>
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
											<div class="col-header col-photo">
                        <carousel
                          v-model="curPicture"
                          :navigationEnabled="true" 
                          :scrollPerPage="false" 
                          :paginationEnabled="false"
                          :mouseDrag="true"
                          :touchDrag="true"
                          :loop="true"
                          :perPage="1"
                          :draggableEnable="false"
                          navigationNextLabel="<i class='fa fa-chevron-right'></i>"
                          navigationPrevLabel="<i class='fa fa-chevron-left'></i>"
                        >
                          <slide v-for="(el, ind) in product.images" class="image-slider image-slider-big" :key="ind" >
                            <a class="lightbox-img" @mousedown="mouseDownGal($event)" @click = "clickGallery($event, ind)">
                              <img :src="el">
                            </a>
                            
                          </slide>
                          
                        </carousel>
                        <vue-gallery :images="product.images" :index="indexGallery" @close="indexGallery = null"></vue-gallery>
                        <carousel
                          class="small"
                          v-model="curPicture"
                          :navigationEnabled="true" 
                          :scrollPerPage="false" 
                          :paginationEnabled="false"
                          :mouseDrag="true"
                          :touchDrag="true"
                          :loop="true"
                          :perPage="4"
                          :draggableEnable="false"
                          navigationNextLabel="<i class='fa fa-chevron-right'></i>"
                          navigationPrevLabel="<i class='fa fa-chevron-left'></i>"
                        >
                          <slide v-for="(el, ind) in product.images2" class="image-slider image-slider-small" :key="ind">
                            <a @click="onMouseEnter(ind)" class="lightbox-img" :class="{active: curPicture == ind}">
                              <img :src="el">
                            </a>
                          </slide>
        
                        </carousel>
													
													
											</div>
									</div>
							</div>
					</div>
				</template>
		</div>
</template>

<script>
	  import { mapGetters } from 'vuex';
		import Breadcrump from '../system/breadcrump.vue';
    import voblers from './product/voblers.vue';
    import owlCarousel from '../system/owl-carousel.vue';
    import Carousel from '../Carousel/Carousel.vue';
    import Slide from '../Carousel/Slide.vue';
		import { dragscroll } from 'vue-dragscroll';
    import notFoundComp from './general/not-found-comp.vue';
    import VueGallery from '../Gallery/gallery.vue';
    //import NoSSR from 'vue-no-ssr';
		export default {
				data() {
						return {
							 curPicture: 0,
               indexGallery: null,
               clientX: 0,
               clientY: 0
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
							return this.product.name || 'Страница не найдена';
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
            onMouseEnter(ind) {
              this.curPicture = ind;
            },
          clickGallery(e, ind) {
            let difX = e.clientX - this.clientX;
            if (difX<0) difX = -difX;
            let difY = e.clientY - this.clientY;
            if (difY<0) difY = -difY;
            if (difX<5 && difY<5) {
              this.indexGallery = ind; 
            }
          },
          mouseDownGal(e) {
            this.clientX = e.clientX;
            this.clientY = e.clientY;
          }
				},
				components: {
						'bread-crump': Breadcrump,
						voblers,
            Carousel,
            Slide,
            owlCarousel,
            notFoundComp,
            VueGallery
				},
				beforeRouteEnter (to, from, next) {
					next(vm => {
						vm.$store.commit('setNonVisibleAside', true);
					})
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
		.item-header .col-header.col-photo .VueCarousel-wrapper {
        padding: 0;
        border-left: solid 25px rgba(0, 0, 0, 0);
    }
    
    .item-header .col-header.col-photo .image-slider.image-slider-big .slide-drg {
        text-align: center;
		}

		.item-header .col-header.col-photo .VueCarousel-navigation-prev, .item-header .col-header.col-photo .VueCarousel-navigation-next {
				display: inline-block;
				position: absolute;
        opacity: .5;
        height: 100%;
        width: 30px;
        outline: none;
		}
		.item-header .col-header.col-photo .VueCarousel-navigation-prev {
				left: 27px;
		}
		.item-header .col-header.col-photo .VueCarousel-navigation-next {
				right: 22px;
		}

		.item-header .col-header.col-photo .VueCarousel-navigation-prev i, .item-header .col-header.col-photo .VueCarousel-navigation-next i {
				text-align: center;
				font-size: 20px;
				text-decoration: none;
				width: 100%;
				color: #b3b3b3;
		}
		.item-header .col-header.col-photo .VueCarousel-navigation-prev:hover, .item-header .col-header.col-photo .VueCarousel-navigation-next:hover {
				background: #e6e6e6;
		}
		.item-header .col-header.col-photo .image-slider-small .lightbox-img {
				border-radius: 5px;
				border: 1px solid #ddd;
		}
		.item-header .col-header.col-photo .image-slider-small .lightbox-img.active {
				border-color: #777;
		}

		.item-header::after {
				content: " ";
				display: table;
				clear: both;
		}

    .item-header .VueCarousel.small {
      margin-top: 10px;
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
				.item-header .col-header.col-photo .VueCarousel.small {
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