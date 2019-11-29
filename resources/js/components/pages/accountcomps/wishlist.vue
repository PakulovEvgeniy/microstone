<template>
    <div v-show="mounted" class="account-wishlist">
      <div class="account-wishlist__info">
          <div class="main-block">
            <div class="name">Список товаров {{curDate}}</div>
            <div class="products-count">{{qtyWishlistText}}</div>
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
              </p><br>
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
            </div>
          </div>
        </div>
        <template v-if="countWishlist!=0">
          <div class="action-buttons">
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
          <div class="wishlist-products">
            <div 
              class="wishlist-product" 
              v-for="(el, ind) in wishProducts"
              :class="{'last': ind == wishProducts.length-1}"
              :key="el.id">
              <div class="image">
                <router-link :to="'/product/'+el.chpu"><img :src="el.image"></router-link>
              </div>
              <div class="wishlist-product__caption">
                <div class="name"><router-link :to="'/product/'+el.chpu">{{el.name}}</router-link></div>
                <div class="product-info__voblers">
                  <voblers></voblers>
                </div>
                <div class="wishlist-product__avail">
                  <avail-links :stock="el.stock" :icon="true"></avail-links>
                </div>
              </div>
              <div class="wishlist-product__price">
                <div class="price">
                  <div v-show="+el.percent" class="previous">
                    <span class="total">{{'от ' + Math.round(el.price*100)/100}}</span>
                    <i class="fa fa-rub"></i>
                    <span class="percent">{{'- '+el.percent+'%'}}</span>
                  </div>
                  <div class="price_g">
                    <span>{{+el.percent ? ('от '+Math.round((+el.price-(+el.percent/100*el.price))*100)/100) : 'от ' +Math.round(el.price*100)/100}}</span>
                    <i class="fa fa-rub"></i>
                  </div>
                </div>
              </div>
              <div class="wishlist-product__buttons">
                <button v-tooltip.bottom="'Добавить в корзину'" class="buy button-ui button-ui_brand button-ui_passive">Купить</button>
                <button v-tooltip.bottom="'Добавить в сравнение'" class="button-ui button-ui_white button-ui_icon">
                  <i class="fa fa-bar-chart"></i>
                </button>
              </div>
              <a @click="delFromWish(el.id)" class="wishlist-product__remove"><span>Удалить</span></a>
            </div>
          </div>
        </template>
      </div>
      <v-dialog/>
    </div>
</template>

<script>
    import { mapGetters, mapActions } from 'vuex';
    import voblers from '../product/voblers.vue';
    import availLinks from '../product/avail-links.vue';
    export default {
        data() {
            return {
            }
        }, 
        props: [
        ],
        components: {
          voblers,
          availLinks
        },
        computed: {
          ...mapGetters([
           'auth',
           'countWishlist',
           'wishlist',
           'wishProducts',
           'mounted'
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
          }
        },
        methods: {
          ...mapActions([
            'clearLocalWishlist',
            'delFromLocalWishlist'
          ]),
          delFromWish(id) {
            if (!this.auth) {
              this.delFromLocalWishlist(id);
            }
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
  .account-wishlist {
    &__info {
      border: 1px solid #ddd;
      padding: 20px;
      .main-block {
        position: relative;
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
  }

  .wishlist-products {
    .wishlist-product {
      transition: padding-left .3s ease 0s;
      border-bottom: 1px solid #ddd;
      padding: 30px 20px;
      position: relative;
      display: flex;
      align-items: center;
      white-space: nowrap;
      &.last {
        border-bottom-left-radius: 8px;
        border-bottom-right-radius: 8px;
        border-bottom: none;
      }
      .image {
        img, a {
          display: block;
        }
      }
      &__caption {
        flex-grow: 1;
        margin-left: 13px;
        .name {
          padding-bottom: 10px;
          a {
            color: #333;
          }
        }
      }
      &__price {
        transition: width .3s ease 0s;
        text-align: right;
        .price {
          font-size: 24px;
        }
        .previous {
          font-style: normal;
          font-size: 13px;
          .total {
            text-decoration: line-through;
          }
          i {
            margin-left: 5px;
            color: #b2b2b2;
          }
          .percent {
            font-size: 12px;
            color: #fff;
            padding: 1px 5px 1px 2px;
            background-color: rgba(29, 113, 184,0.9);
            position: relative;
            margin-left: 8px;
            &:before {
              content: '';
              border: 8px solid transparent;
              border-right-color: rgba(29, 113, 184,0.9);
              position: absolute;
              left: -16px;
              top: 0;
            }
          }
        }
        .price_g {
          font-size: 20px;
          color: #333;
          font-weight: bold;
          i {
            margin-left: 5px;
            color: #b2b2b2;
            font-weight: normal;
          }
        }
      }
      &__buttons {
        margin-left: 13px;
        button.buy {
          padding-left: 15px;
          padding-right: 15px;
        }
      }
      &__remove {
        transition: opacity .6s ease 0s;
        border-bottom: 1px dotted gray;
        color: gray;
        display: block;
        font-size: 12px;
        opacity: 1;
        position: absolute;
        right: 20px;
        top: 10px;
        &:hover {
          text-decoration: none;
        }
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
    }
  }
</style>