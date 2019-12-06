<template>
    <div v-show="mounted" class="account-wishlist">
      <div class="account-wishlist__info">
          <div class="main-block">
            <div class="name">{{auth ? 'Общий список' : 'Список товаров'}} {{curDate}}</div>
            <div class="products-count">{{qtyWishlistText}}</div>
          </div>
          <div v-if="countWishlist" class="right-block">
            <div class="price-line">
              <div class="price">
                <span>{{totalPrice}}</span>
                <i class="fa fa-rub"></i>
              </div>
              <button 
                @click="addAllToCart" 
                class="buy button-ui button-ui_brand"
                :class="{'active': allInCart}"
              >
                {{allBtnText}}
              </button>
            </div>
            <div class="order-avail-wrap">
              <avail-links :stock="haveStock" :icon="true"></avail-links>
            </div>
          </div>
      </div>
      <div class="account-wishlist__body">
        <div v-if="countWishlist==0">
          <div class="add-product-to-wish">
            <div class="b-icon">
              <i class="fa fa-heart-o"></i>
            </div>
            <div class="b-text">
              <h4>Добавьте товар в избранное</h4>
              <p>В этом списке будут храниться товары, которые Вас заинтересовали.<br>
                Можете добавить сюда недавно просмотренные товары.
              </p>
              <template v-if="!auth">
                <br>
                <p>
                  <b>Внимание!</b><br>
                  Чтобы сохранить список и иметь доступ к нему с различных устройств,<br>
                  необходимо
                  <router-link to="/login">авторизоваться</router-link> или 
                  <router-link to="/register">зарегистрироваться</router-link>
                </p><br>
                <p>Кроме того, зарегистрированные пользователи,<br>получают возможность загружать <b>произвольные спецификации</b><br>
                  и осуществлять поиск оптимальных предложений.
                </p>
              </template>
            </div>
          </div>
        </div>
        <template v-if="countWishlist!=0">
          <div v-if="!auth" class="action-buttons">
            <div class="guest-info">
              <b>Если вы не авторизуетесь, список может быть удален.</b>
              Чтобы сохранить список и иметь к нему доступ с различных устройств,
              <router-link to="/login">авторизуйтесь</router-link> или 
              <router-link to="/register">зарегистрируйтесь.</router-link><br>
              Авторизованные пользователи могут создавать любое количество списков, а также загружать произвольные спецификации.
            </div>
            <a @click="clearWish" class="clear-wishlist">
              <i class="fa fa-trash-o"></i>Очистить список
            </a>
          </div>
          <wishlist-products v-if="!auth || wishCurGroup || wishCurGroup === 0" :wishProducts="wishProducts"></wishlist-products>
          <wishlist-products-small v-else :wishProducts="wishProducts"></wishlist-products-small>
        </template>
      </div>
      <div v-if="auth && wishCurGroup===null" 
        class="account-wishlist__newlist"
        @click="newList"
      >
        <i class="fa fa-plus"></i>
        Создать новый список
      </div>
      <v-dialog/>
    </div>
</template>

<script>
    import { mapGetters, mapActions } from 'vuex';
    import availLinks from '../product/avail-links.vue';
    import wishlistProducts from './wishlist/wishlist-products.vue';
    import wishlistProductsSmall from './wishlist/wishlist-products-small.vue';
    import addListDialog from './wishlist/add-list-dialog.vue';
    export default {
        data() {
            return {
            }
        }, 
        props: [
        ],
        components: {
          availLinks,
          wishlistProducts,
          wishlistProductsSmall
        },
        computed: {
          ...mapGetters([
           'auth',
           'countWishlist',
           'wishlist',
           'wishProducts',
           'compare',
           'mounted',
           'cart',
           'wishCurGroup'
          ]),
          curDate() {
            let today = new Date();
            let dd = today.getDate();
            let mm = today.getMonth() + 1; //January is 0!
            let yyyy = today.getFullYear();
            if (dd < 10) {
              dd = '0' + dd;
            } 
            if (mm < 10) {
              mm = '0' + mm;
            } 
            return dd + '.' + mm + '.' + yyyy;
          },
          qtyWishlistText() {
            if (!this.countWishlist) {
              return 'Нет товаров';
            }
            return this.countWishlist + ' ' + this.goodsEnd(this.countWishlist) + ' (' + this.curDate + ')';
          },
          totalPrice() {
            //if (this.auth) return 0;
            let total = this.wishProducts.reduce((acc, el) => {
              return acc + ((+el.percent) ? (Math.round((+el.price-(+el.percent/100*el.price))*100)/100) : Math.round(el.price*100)/100);
            }, 0);
            return 'от ' + total;
          }, 
          allInCart() {
            //if (this.auth) return false;
            let res = this.wishProducts.every((el) => {
              return this.cart.items.indexOf(el.id) != -1;
            });
            return res;
          },
          haveStock() {
            //if (this.auth) return false;
            let res = this.wishProducts.some((el) => {
              return el.stock;
            });
            return res;
          },
          allBtnText() {
            return this.allInCart ? 'Всё в корзине' : 'Купить все';
          }
        },
        methods: {
          ...mapActions([
            'clearLocalWishlist',
            'delFromLocalWishlist',
            'addArrayToLocalCart'
          ]),
          newList() {
            this.$modal.show(addListDialog, {
              holder: 'Мой список ' + this.curDate,
              actFunc: this.addList
            }, {
              width: 350,
              height: 'auto'
            });
          },
          addList(val) {
            this.$store.dispatch('queryPostToServer', {
                url: '/account/wishlist/addgroup',
                params: {
                  name: val ? val : 'Мой список ' + this.curDate
                }
            });
          },
          goodsEnd(qty) {
            let c = qty % 100;
            let a1 = [1, 21, 31, 41, 51, 61, 71, 81, 91];
            let a2 = [2, 3, 4, 22, 23, 24, 32, 33, 34, 42, 43, 44, 52, 53, 54, 62, 63, 64, 72, 73, 74, 82, 83, 84, 92, 93, 94];
            if (a1.indexOf(c) != -1) {
              return 'товар';
            } else if(a2.indexOf(c) != -1) {
              return 'товара';
            } else {
              return 'товаров';
            }
          },
          addAllToCart() {
            if (!this.auth) {
              if (!this.allInCart) {
                let arr = this.wishProducts.map((el) => {
                  return el.id;
                });
                this.addArrayToLocalCart(arr); 
              } else {
                this.$router.push('/account/cart');
              }
            }
          },
          clearWish() {
            if (!this.auth) {
              this.$modal.show('dialog', {
                text: 'Вы действительно хотите очистить список избранного?',
                buttons: [
                  {
                    title: 'ОК',
                    handler: () => { 
                      this.clearLocalWishlist();
                      this.$modal.hide('dialog');
                    }
                  },
                  {
                    title: 'Отмена'
                  }
                ]
              })
            }
          }
        },
        mounted() {
          if (!this.auth) {
            this.$store.dispatch('restoreWishList');
            if (this.countWishlist>0) {
              this.$store.dispatch('queryGetToServer', {
                url: '/api/products/wishlist',
                params: {
                  pr: this.wishlist.items
                }
              });
            } else {
              this.$store.commit('setWishlistProducts', []);
            }
          }
        }
    }
</script>

<style lang="less">
@import '../../../../less/vars.less';
  .account-wishlist {
    &__info {
      display: flex;
      border: 1px solid #ddd;
      padding: 20px;
      .main-block {
        position: relative;
        width: calc(100% - 300px);
        .name {
          font-size: 18px;
          margin-bottom: 5px;
          word-wrap: break-word;
        }
        .products-count {
          color: gray;
          font-size: 13px;
        }
      }
      .right-block {
        width: 300px;
        .price-line {
          display: flex;
          align-items: center;
          button {
            padding-left: 20px;
            padding-right: 20px;
            margin-left: 20px;
            width: auto;
          }
        }
        .price {
          text-align: right;
          color: #333;
          font-size: 20px;
          font-weight: bold;
          flex-grow: 1;
          i {
            margin-left: 5px;
            color: #b2b2b2;
            font-weight: normal;
          }
        }
        .order-avail-wrap {
          text-align: right;
          margin-top: 10px;
        }
      }
    }
    &__body {
      border: 1px solid #ddd;
      border-top: none;
      .add-product-to-wish {
        padding: 30px;
        .b-icon {
          color: #d8d8d8;
          display: inline-block;
          font-size: 60px;
          margin-right: 20px;
          vertical-align: top;
        }
        .b-text {
          color: gray;
          display: inline-block;
          h4 {
            font-size: 24px;
            font-weight: bold;
            margin: 0 0 20px;
          }
          p {
            margin: 0;
          }
          a {
            color: #0094d9;
            text-decoration: none;
            &:hover {
              color: #00608d;
              text-decoration: underline;
            }
          }
        }
      }
      .action-buttons {
        transition: background-color .3s ease 0s;
        background-color: #f6f6f6;
        border-bottom: 1px solid #ddd;
        height: 125px;
        padding: 30px 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        a {
          color: #0094d9;
          text-decoration: none;
          &:hover {
            color: #00608d;
            text-decoration: underline;
          }
          &.clear-wishlist {
            color: gray;
            font-size: 13px;
            i {
              margin-right: 5px;
            }
            &:hover {
              text-decoration: none;
            }
          }
        }
        .guest-info {
          max-width: 80%;
        }
      }
    }
    &__newlist {
      border: 2px dashed #ddd;
      cursor: pointer;
      font-size: 18px;
      margin-bottom: 30px;
      margin-top: 30px;
      padding: 30px 20px 30px 50px;
      position: relative;
      i {
        color: #ddd;
        display: inline-block;
        font-size: 25px;
        vertical-align: middle;
        margin-left: -20px;
        margin-right: 20px;
      }
      &:hover {
        border-color: @main-color;
        border-style: solid;
      }
    }
  }

  

  @media (min-width: 992px) {
    .account-wishlist {
      &__info {
        border-top-right-radius: 8px;
        border-top-left-radius: 8px;
      }
      &__body {
        border-bottom-right-radius: 8px;
        border-bottom-left-radius: 8px;
      }
      &__newlist {
        border-radius: 8px;
      }
    }
  }
</style>