<template>
		<div class="cart-goods">
			<div class="cart-goods__main">
				<div class="inner">
					<div class="cart-goods__top-controls">
						<a @click="onClearCart"><i class="fa fa-trash-o" aria-hidden="true"></i><span>Очистить корзину</span></a>
					</div>
					<div v-for="el in cartItems" :key="el.id+el.characteristic" class="cart-goods__product">
						<div class="cart-goods__product-thumb">
							<div class="cart-goods__product-info">
								<div class="cart-goods__product-image">
									<router-link :to="'/product/'+el.product.chpu">
										<img :src="el.product.image">
									</router-link>
								</div>
							
								<div class="cart-goods__product-name">
									<router-link :to="'/product/'+el.product.chpu">{{el.product.name}}</router-link>
									<div v-if="el.product.have_charact && el.charactItem" class="characteristic">
										{{el.charactItem.name}}
									</div>
									<div class="manuf">
										<span>{{el.manufacturer}}, </span>
										<span>{{el.product.cat_name}}</span>
									</div>
									<div class="sku">
										Код товара: {{el.product.sku}}
									</div>
								</div>
							</div>
							<order-blockcart :element="el"></order-blockcart>
						</div>
						<div class="cart-goods__product-controls">
							<special-buttons :product="el.product" :curCharact="el.charactItem"></special-buttons>
							<a v-tooltip.top="'Удалить из корзины'" @click="onDelete(el)"><i class="fa fa-trash-o" aria-hidden="true"></i><span>Удалить</span></a>
						</div>
					</div>
					<div v-if="settings.order_discount" class="cart-goods__discount"><sup>*</sup> Для позиций под заказ дополнительно предоставляется скидка {{settings.order_discount}}%</div>
					<div class="cart-goods__addbutton">
						<div class="cart-goods__search"><a @click="isSearch=!isSearch" class="button-ui button-ui_brand">Добавить товары</a>
	        	</div>
						<search-module
	            :notBuy="true"
	            :fromCart="true" 
	            v-if="isSearch"
	            @close="isSearch=false"
	          >
	        	</search-module>
        	</div>
				</div>
			</div>
			<div :style="{'padding-top' : padding+'px'}" class="cart-goods__right">
				<div class="inner">
					Всего {{totalQty}} {{goodsEnd(totalQty)}} на сумму:
					<div class="total">{{totalPrice}}<i class="fa fa-rub"></i></div>
				</div>
				<div class="cart-goods__oform"><button @click="onOform" class="button-ui button-ui_brand">Оформить заказ</button>
        </div>
			</div>
		</div>
</template>

<script>
		import { mapGetters } from 'vuex';
		import orderBlockcart from '../product/order-blockcart.vue';
		import specialButtons from '../product/special-buttons.vue';
		import searchModule from '../../system/search-module.vue';
		import {goodsEnd} from '../../../utils/utils.js';
		import authDialog from '../../forms/auth-dialog.vue';
		export default {
				data() {
						return {
							isSearch: false
						}
				},
				computed: {
					...mapGetters([
						'cartProducts',
						'cart',
						'auth',
						'cartQty',
						'settings',
						'scrolled',
						'isVerify'
					]),
					padding() {
						return Math.max(this.scrolled-50, 0);
					},
					cartItems() {
						if (!this.cartProducts || !this.cartProducts.length || !this.cart.items || !this.cart.items.length) return [];
						return this.cart.items.map((el) => {
							let prd = this.cartProducts.find((pr) => {
								return el.id == pr.id;
							}) || {};
							let chi;
							let mnf = prd.manuf && prd.manuf.length ? prd.manuf[0].name : '';
							if (prd && prd.have_charact) {
								chi = prd.characts.find((chrt) => {
									return el.characteristic == chrt.id;
								});
							}
							let price  = 0;
							if (prd && prd.characts_price) {
								if (chi) {
									let curP = prd.characts_price.find((el) => {
                    return el.id == chi.id;
                  });
                  if (curP) {
                    price = (+curP.price).toFixed(2);
                  }
								} else {
									price = (+prd.characts_price.min).toFixed(2);
								}
							}
							let listDiscount = [];
							if (prd && prd.discount_group && prd.discount_group.length) {
								listDiscount = prd.discount_group.map((el) => {
		              let pr = (Math.ceil(price*(1-el.discount/100)*10)/10).toFixed(2);
		              return {text:'от '+el.qty+' шт. - '+pr, price: pr, qty: el.qty};
		            }).sort((el1, el2) => {
		              return el1.qty - el2.qty;
		            });
		            listDiscount.unshift({text:'от '+1+' шт. - '+ price, price: price, qty: 1});
							}
							let qtyStock = 0;
							if (prd && prd.stock_charact) {
								let charact = (prd.have_charact && chi) ? chi.id : '';
	              let it = prd.stock_charact.find((el) => {
	                return el.id == prd.id && el.characteristic == charact;
	              });
              	qtyStock = it ? +it.stock : 0;
							}
							let qtyStockBuy = Math.min(qtyStock, el.qty);
							let qtyOrderBuy = el.qty - qtyStockBuy;

							let priceViz = price;
							for (let i = listDiscount.length-1; i >= 0; i--) {
	              if (el.qty>=listDiscount[i].qty) {
	                priceViz = listDiscount[i].price;
	                break;
	              }
	            }
	            priceViz = (+priceViz).toFixed(2);
	            let priceVizOrder = this.settings.order_discount ? priceViz * ((100-this.settings.order_discount)/100) : priceViz;
	            priceVizOrder = (+priceVizOrder).toFixed(2);
           
	            let totalPrice = (qtyStockBuy * priceViz + qtyOrderBuy * priceVizOrder).toFixed(2);

							return {
								...el,
								product: prd,
								charactItem: chi,
								manufacturer: mnf,
								listDiscount,
								qtyStockBuy,
								qtyOrderBuy,
								priceViz,
								priceVizOrder,
								totalPrice 
							}
						});
					},
					totalQty() {
						return this.cartItems.length;
					},
					totalPrice() {
						if (this.totalQty == 0) {
							return 0;
						}
						let sum = 0;
						this.cartItems.forEach((el) => {
							sum += +el.totalPrice;
						});
						return sum.toFixed(2);
					}
				},
				methods: {
					onOform() {
						if (!this.auth || (this.auth && !this.isVerify)) {
							 this.$modal.show(authDialog, {

		            }, {
		              width: 450,
		              height: 'auto'
		            });
						}
					},
					onDelete(el) {
						this.$store.dispatch('deleteItemFromCart', [
							{
								id: el.id,
								characteristic: el.characteristic
							}
						]);
					},
					onClearCart() {
						this.$store.dispatch('clearCart');
					},
					goodsEnd(qty) {
						return goodsEnd(qty);
					}
				},
				components: {
					orderBlockcart,
					specialButtons,
					searchModule
				},
				mounted() {
					//if (!this.auth) {
						this.$store.dispatch('queryPostToServer', {
							url: '/account/cart',
							params: {
								'items': this.cart.items
							}
						});
					//}
				},
				watch: {
					cartQty(val, oldVal) {
						if (val>oldVal) {
							this.$store.dispatch('queryPostToServer', {
								url: '/account/cart',
								params: {
									'items': this.cart.items
								}
							});
						}
					}
				}
		}
</script>

<style lang="less">
@import '../../../../less/smart-grid.less';
@import '../../../../less/vars.less';
	.cart-goods {
		.row-flex();
		&__main > .inner, &__right > .inner {
			border-radius: 8px;
      background: #fff;
    	padding: 10px;
		}
		&__discount {
			margin-top: 20px;
			color: gray;
		}
		&__main {
			.col();
			.size(18);
    	.size-sm(24);
    	padding-right: 15px;
    	.sm-block({
    		padding-right: 0;
    		margin-bottom: 10px;
    	});
		}
		&__right {
			.col();
			.size(6);
    	.size-sm(24);
    	.xs-block({padding-top: 0 !important;});
    	.inner {
    		text-align: center;
    		.total {
    			font-size: 22px;
    			font-weight: bold;
    			margin-top: 10px;
    			i {
    				margin-left: 5px;
    			}
    		}
    	}
		}
		&__oform {
			margin-top: 10px;
			button {
				width: 100%;
			}
		}
		&__addbutton {
			margin: 15px -10px 0 -10px;
			position: relative;
			.search-module {
				bottom: 10px;
			}
		}
		&__search {
			> a {
				line-height: 40px;
        padding: 0 20px;
        width: 220px;
        margin-left: 10px;
        margin-bottom: 10px;
        &:hover {
          color: #fff;
        }
			}
		}
		&__top-controls {
			margin-bottom: 15px;
			text-align: right;
			a { 
				color: gray;
				span {
					color: #0094d9;
	    		border-bottom: 1px dotted #0094d9;
				}
				i {
					margin-right: 5px;
				}
			}
		}
		&__product {
			margin-bottom: 10px;
			border-bottom: 1px solid #ccc;
			padding-bottom: 10px;
			&-thumb {
				.row-flex();
				align-items: center;
				.order-blockcart {
					.col();
					.size(14);
					.size-xs(24);
				}
			}
			&-info {
				.col();
				.size(10);
				.size-xs(24);
				display: flex;
				align-items: center;
				.xs(margin-bottom, 10px);
			}
			&-name {
				flex-grow: 1;
				.characteristic {
					color: #999;
    			font-size: 14px;
				}
				.manuf {
					font-size: 13px;
				}
				.sku {
					color: #777;
    			font-size: 11px;
				}
				a:hover {
					color: @main-color;
				}
			}
			&-controls {
				.special-buttons {
					display: inline-block;
				}
				 a {
				 	color: gray;
				 	span {
				 		color: #0094d9;
    				border-bottom: 1px dotted #0094d9;
				 	}
				 	i {
				 		margin-right: 5px;
				 	}
				 }
			}
		}
	}	
</style>