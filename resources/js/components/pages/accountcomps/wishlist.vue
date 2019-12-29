<template>
    <div v-show="mounted" class="account-wishlist">
      <div v-if="auth && wishCurGroup !== null" class="account-wishlist__head">
        <router-link to="/account/wishlist" class="button-ui button-ui_white back">Назад</router-link>
        <dropdown-menu>
          <template v-slot:activator>
            <i class="fa fa-chevron-down"></i>  
          </template>
          <li><router-link to="/account/wishlist/0">Общий список</router-link></li>
          <li v-for="el in listNotArchived" :key="el.id"><router-link :to="'/account/wishlist/'+el.id">{{el.name}}</router-link></li>
        </dropdown-menu>
      </div>
      <div class="account-wishlist__info">
          <div class="main-block">
            <div class="name">{{nameList}}</div>
            <div class="products-count">{{qtyWishlistText}}</div>
          </div>
          <div v-if="wishProducts.length" class="right-block">
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
        <div v-if="wishProducts.length==0">
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
        <template v-if="wishProducts.length!=0">
          <div v-if="!auth || wishCurGroup!==null" class="action-buttons" :class="{'auth': auth, 'selectable': select}">
            <div v-if="!auth" class="guest-info">
              <b>Если вы не авторизуетесь, список может быть удален.</b>
              Чтобы сохранить список и иметь к нему доступ с различных устройств,
              <router-link to="/login">авторизуйтесь</router-link> или 
              <router-link to="/register">зарегистрируйтесь.</router-link><br>
              Авторизованные пользователи могут создавать любое количество списков, а также загружать произвольные спецификации.
            </div>
            <template v-if="auth">
              <dropdown-menu v-if="listCategory.length>2" 
                icon_after="fa fa-chevron-down"
                add_class="visible-on-selection"
              >
                <template v-slot:activator>
                   {{curCatName}} 
                </template>
                <li @click="curCatId=el.id" v-for="el in listCategory" :key="el.id"><a>{{el.name}}</a></li>
              </dropdown-menu>
              <div class="selection-panel visible-on-selection">
                <span class="product-counter">{{qtyChecked ? qtyChecked : ''}}</span>
                <div @click="onAllCheck" class="m-checkbox">
                  <input type="checkbox" :checked="allChecked">
                  <label><i class="fa fa-check"></i>Выделить все</label>
                </div>
                <a @click="addToOtherList(listChecked, selType=='cart')" class="apply" :class="{disabled: !qtyChecked}">{{selType=='list' ? 'Выбрать список' : 'Добавить в корзину'}}</a>
                <a @click="select=false" class="cancel">Отменить</a>
              </div>
              <button @click="select=true; selType='list'" class="m-btn m-btn-default">Добавить товары в другой список</button>
              <button @click="select=true; selType='cart'" class="m-btn m-btn-default">Добавить товары в корзину</button>
            </template>
            <div class="act-list">
              <a @click="clearWish" class="clear-wishlist">
                <i class="fa fa-trash-o"></i>Очистить список
              </a>
              <a v-if="wishCurGroup && curList" class="rename" @click.stop="renameCustom(curList)">
                <i class="fa fa-pencil-square-o"></i>Переименовать
              </a>
              <a v-if="wishCurGroup && curList" class="archive" @click.stop="archiveAct(curList, 1)">
                <i class="fa fa-file-archive-o"></i>В архив
              </a>
            </div>
          </div>
          <wishlist-products 
            v-if="!auth || wishCurGroup || wishCurGroup === 0" 
            :wishProducts="wishProductsFiltr"
            :curGroup = 'wishCurGroup'
            :select="select"
            :prodChecked="prodChecked"
            :qtyChecked="qtyChecked"
            @addToOtherList="addToOtherList($event)"
            @updateCheckProds="updateCheckProds($event)"
            @cancelSelection="select=false"
          ></wishlist-products>
          <wishlist-products-small v-else :wishProducts="wishProducts"></wishlist-products-small>
        </template>
      </div>
      <div v-if="auth && wishCurGroup===null && listNotArchived.length" class="account-wishlist__list">
        <h3>Созданные списки ({{listNotArchived.length}})
          <anchor-router-link v-if="listArchived.length" :to="{hash: '#tocArch'}"
            :scrollOptions="{
                container: 'body',
                duration: 700,
                easing: 'ease'
              }"
          >
            <i class="fa fa-file-archive-o"></i>В архиве (<span>{{listArchived.length}}</span>)
          </anchor-router-link>
        </h3>
        <div 
          class="custom"
          v-for="el in listNotArchived"
          :key="el.id"
          @click = "customClick(el.id)"
        >
          <div class="main-info">
            <div class="name">{{el.name}}</div>
            <div class="count">{{el.count ? el.count + ' ' + goodsEnd(el.count) : 'Нет товаров'}}</div>
          </div>
          <div class="action-buttons">
            <a @click.stop="renameCustom(el)" class="rename"><i class="fa fa-pencil-square-o"></i><span>Переименовать</span></a>
            <a @click.stop="archiveCustom(el, 1)" class="archive"><i class="fa fa-file-archive-o"></i><span>Архивировать</span></a>
          </div>
          <div class="price"><span v-if="el.count">{{'от ' + el.total_price}}<i class="fa fa-rub"></i></span></div>
          <i class="fa fa-chevron-right"></i>
        </div>
      </div>
      <div v-if="auth && wishCurGroup===null" 
        class="account-wishlist__newlist"
        @click="newList"
      >
        <i class="fa fa-plus"></i>
        Создать новый список
      </div>
      <div id="tocArch" v-if="auth && wishCurGroup===null && listArchived.length" class="account-wishlist__archive">
        <h3>Архив ({{listArchived.length}})
          <a @click="archOpen=!archOpen" class="toggle-archived">{{archOpen ? 'Скрыть' : 'Развернуть'}}</a>
        </h3>
        <div v-if="archOpen">
          <div
            class="custom"
            v-for="el in listArchived"
            :key="el.id"
            @click = "customClick(el.id)"
          >
            <div class="main-info">
              <div class="name">{{el.name}}</div>
              <div class="count">{{el.count ? el.count + ' ' + goodsEnd(el.count) : 'Нет товаров'}}</div>
            </div>
            <div class="action-buttons">
              <a @click.stop="archiveCustom(el, 0)" class="rename"><i class="fa fa-reply"></i><span>Вернуть</span></a>
              <a @click.stop="delCustom(el)" class="archive"><i class="fa fa-times"></i><span>Удалить</span></a>
            </div>
            <div class="price"><span v-if="el.count">{{'от ' + el.total_price}}<i class="fa fa-rub"></i></span></div>
            <i class="fa fa-chevron-right"></i>
          </div>
        </div>
      </div>
      <v-dialog/>
    </div>
</template>

<script>
    import { mapGetters, mapActions } from 'vuex';
    import AnchorRouterLink from '../../system/vue-anchor-router-link.vue';
    import availLinks from '../product/avail-links.vue';
    import wishlistProducts from './wishlist/wishlist-products.vue';
    import wishlistProductsSmall from './wishlist/wishlist-products-small.vue';
    import addListDialog from './wishlist/add-list-dialog.vue';
    import addToOther from './wishlist/add-to-other-list-dialog.vue';
    import dropdownMenu from '../../system/drop-down.vue';
    export default {
        data() {
            return {
              archOpen: false,
              curCatId: 0,
              select: false,
              prodChecked: [],
              selType: 'list'
            }
        }, 
        props: [
        ],
        components: {
          availLinks,
          wishlistProducts,
          wishlistProductsSmall,
          AnchorRouterLink,
          dropdownMenu
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
           'wishCurGroup',
           'wishCurName',
           'wishGroups'
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
          nameList() {
            if (!this.auth) {
              return 'Список товаров ' + this.curDate;
            } else {
              if (!this.wishCurGroup) {
                return 'Общий список ' + this.curDate;
              } else {
                return this.wishCurName;
              }
            }
          },
          qtyWishlistText() {
            if (!this.wishProducts.length) {
              return 'Нет товаров';
            }
            return this.wishProducts.length + ' ' + this.goodsEnd(this.wishProducts.length) + ' (' + this.curDate + ')';
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
            let res = this.wishProductsFiltr.every((el) => {
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
          },
          listNotArchived() {
            return this.wishGroups.filter((el) => {
              return el.archived == 0
            });
          },
          listArchived() {
            return this.wishGroups.filter((el) => {
              return el.archived == 1
            });
          },
          curList() {
            return this.wishGroups.find((el) => {
              return el.id == this.wishCurGroup
            });
          },
          listCategory() {
            if (!this.auth) {
              return [];
            }
            let res = [];
            res.push({
              name: 'Все товары',
              id: 0
            });
            let i = 0;
            this.wishProducts.forEach((el1) => {
              let f = res.find((el2) => {
                return el2.name == el1.cat_name;
              })
              if (!f) {
                res.push({
                  name: el1.cat_name,
                  id: ++i
                });
              }
            });
            return res;
          },
          curCatName() {
            if (!this.auth) {return ''}
            let it = this.listCategory.find((el) => {
              return el.id == this.curCatId;
            });
            return it ? it.name : '';
          },
          wishProductsFiltr() {
            if (!this.auth) {
              return this.wishProducts;
            } else {
              if (this.curCatId==0) {
                return this.wishProducts;
              } else {
                let lCat = this.listCategory.find((el) => {
                  return el.id == this.curCatId;
                });
                if (!lCat) {
                  return this.wishProducts;
                }
                return this.wishProducts.filter((el) => {
                  return el.cat_name == lCat.name;
                })
              }
            }
          },
          allChecked() {
            return this.wishProductsFiltr.every((el) => {
              return this.prodChecked.indexOf(el.id) != -1
            });
          },
          listChecked() {
            return this.wishProductsFiltr.filter((el) => {
              return this.prodChecked.indexOf(el.id) != -1;
            }).map(el => el.id);
          },
          qtyChecked() {
            return this.listChecked.length;
          }
        },
        methods: {
          ...mapActions([
            'clearLocalWishlist',
            'clearServerWishlist',
            'delFromLocalWishlist',
            'addArrayToLocalCart',
            'addToCart'
          ]),
          updateCheckProds(val) {
            this.prodChecked = val;
          },
          newList() {
            this.$modal.show(addListDialog, {
              holder: 'Мой список ' + this.curDate,
              actFunc: this.addList
            }, {
              width: 350,
              height: 'auto'
            });
          },
          addToOtherListServer(par, isCart) {
            this.$store.dispatch('queryPostToServer', {
              url: '/account/wishlist/addingrouplist',
              params: par
            });  
          },
          addToOtherList(e, isCart) {
            if (isCart !== undefined && !this.qtyChecked) {
              return;
            }
            if (!isCart) {
              this.$modal.show(addToOther, {
                holder: 'Мой список ' + this.curDate,
                curGroup: this.wishCurGroup,
                editList: this.editList,
                addList: this.addList,
                product_id: e,
                actFunc: this.addToOtherListServer
              }, {
                width: 500,
                height: 'auto'
              });
            } else {
              this.addToCart(e);
            }
          },
          addList(val, sucFunc) {
            this.$store.dispatch('queryPostToServer', {
                url: '/account/wishlist/addgroup',
                successAction: sucFunc,
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
            } else {
              if (!this.allInCart) {
                this.addToCart(
                  this.wishProductsFiltr.map(el => el.id)
                )
              } else {
                this.$router.push('/account/cart');
              };
            }
          },
          clearWish() {
            this.$modal.show('dialog', {
              text: 'Вы действительно хотите очистить список?',
              buttons: [
                {
                  title: 'ОК',
                  handler: () => {
                    if (!this.auth) { 
                      this.clearLocalWishlist();
                    } else {
                      this.clearServerWishlist(this.wishCurGroup);
                    }
                    this.$modal.hide('dialog');
                  }
                },
                {
                  title: 'Отмена'
                }
              ]
            })
          },
          customClick(id) {
            this.$router.push('/account/wishlist/'+id);
          },
          renameCustom(el) {
            this.$modal.show(addListDialog, {
              holder: el.name,
              edit: true,
              actFunc: this.editList,
              item: el
            }, {
              width: 350,
              height: 'auto'
            });
          },
          editList(id, val) {
            this.$store.dispatch('queryPostToServer', {
                url: '/account/wishlist/editgroup',
                params: {
                  id: id,
                  name: val
                }
            });
          },
          archiveCustom(el, arch) {
            this.$store.dispatch('queryPostToServer', {
                url: '/account/wishlist/archivegroup',
                params: {
                  id: el.id,
                  arch: arch
                }
            });
          },
          archiveAct(el, arch) {
            this.archiveCustom(el, arch);
            this.$router.push('/account/wishlist/0');
          },
          delCustom(el) {
            this.$modal.show('dialog', {
              text: 'Вы действительно хотите удалить список?',
              buttons: [
                {
                  title: 'ОК',
                  handler: () => { 
                    this.delCustomList(el);
                    this.$modal.hide('dialog');
                  }
                },
                {
                  title: 'Отмена'
                }
              ]
            })
          },
          delCustomList(el) {
            this.$store.dispatch('queryPostToServer', {
                url: '/account/wishlist/delgroup',
                params: {
                  id: el.id
                }
            });
          },
          onAllCheck() {
            let allC = this.allChecked;
            this.wishProductsFiltr.forEach((el) => {
              let ind = this.prodChecked.indexOf(el.id);
              if (allC) {
                if (ind!=-1) {
                  this.prodChecked.splice(ind, 1);
                }
              } else {
                if (ind==-1) {
                  this.prodChecked.push(el.id);
                }
              }
            });
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
        },
        watch: {
          '$route' (to, from) {
            this.curCatId = 0;
            this.select = false;
            this.prodChecked = [];
          }
        }
    }
</script>

<style lang="less">
@import '../../../../less/vars.less';
  .account-wishlist {
    :last-child {
      margin-bottom: 0;
    }
    &__head {
      line-height: 40px;
      margin-bottom: 15px;
      min-height: 35px;
      display: flex;
      a.back {
        padding-right: 20px;
        padding-left: 20px;
        margin-right: 20px;
        &:hover {
          text-decoration: none;
        }
      }
      .menu-dropdown-target {
        border-radius: 8px;
        height: 40px;
        padding: 0;
        font-size: 16px;
        outline: none;
        cursor: pointer;
        text-align: center;
        background: #fff;
        border: 1px solid #d9d9d9;
        min-width: 40px;
        padding: 0;
        color: #8c8c8c;
        display: block;
        &:hover {
          background-image: linear-gradient(to bottom, #2a8cde, #165b92);
          box-shadow: inset 0 -2px 0 0 rgba(0, 0, 0, 0.2);
          color: #fff;
          border: none;
          font-weight: bold;
        }
      }
      .menu-dropdown-items {
        top: 0;
        left: 0;
        font-size: 13px;
        &:before, &:after {
          content: none;
        }
        li:hover {
          background: #f5f5f5;
        }
        a:hover {
          text-decoration: none;
          color: inherit;
        }
      }
    }
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
          white-space: nowrap;
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
      margin-bottom: 50px;
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
        align-items: center;
        a {
          color: #0094d9;
          text-decoration: none;
          &:hover {
            color: #00608d;
            text-decoration: underline;
          }
          &.clear-wishlist, &.rename, &.archive {
            color: gray;
            font-size: 13px;
            white-space: nowrap;
            display: block;
            margin-bottom: 5px;
            i {
              margin-right: 5px;
            }
            &:hover {
              text-decoration: none;
            }
          }
        }
        &.auth {
          height: auto;
          &.selectable {
            background-color: #424a54;
            > :not(.visible-on-selection) {
              display: none;
            }
            .menu-dropdown-target {
              color: #fff;
              + i {
                color: #fff;
              }
            }
            .selection-panel {
              color: #fff;
              display: block;
              width: 100%;
              a {
                border-radius: 4px;
                text-decoration: none;
                float: right;
                margin-left: 30px;
                text-align: center;
                display: inline-block;
                height: 35px;
                line-height: 35px;
                text-align: center;
                &.cancel {
                  color: #fff;
                  width: 90px;
                  &:hover {
                    background: rgba(255,255,255,0.05);
                  }
                }
                &.apply {
                  width: 150px;
                  border: 1px solid #d8d8d8;
                  background: #fff;
                  color: #333;
                  font-size: 13px;
                  white-space: nowrap;
                  &.disabled {
                    cursor: not-allowed;
                    border-color: rgba(51,51,51,0.25);
                    background: rgba(51,51,51,0.25);
                    color: gray;
                  }
                  &:not(.disabled):hover {
                    background: #f5f5f5;
                  }
                }
              }
            }
          }
        }
        .selection-panel {
          display: none;
          line-height: 35px;
          .product-counter {
            width: 3em;
            font-weight: bold;
            text-align: right;
            margin-right: 5px;
            display: inline-block;
            line-height: 35px;
          }
          .m-checkbox {
              display: inline-block;
              label {
                line-height: 35px;
              }
          }
          .m-checkbox input[type="checkbox"] + label::before {
            background: transparent;
            border-color: #fff;
          }
          .m-checkbox input[type="checkbox"]:checked + label::before {
            background: #fff;
          }
          .m-checkbox input[type="checkbox"]:checked + label i {
            line-height: unset;
            top: -10px;
          }
        }
        .guest-info {
          max-width: 80%;
          align-items: center;
        }
        .menu-dropdown {
          margin-right: 10px;
          display: flex;
          align-items: center;
        }
        .m-btn {
          font-size: 13px;
          min-height: 35px;
          + .m-btn {
            margin-right: 15px;
          }
        }
        .menu-dropdown-target {
          color: gray;
          margin-right: 5px;
          max-width: 150px;
          overflow-x: hidden;
          text-overflow: ellipsis;
          white-space: nowrap;
          + i {
            color: gray;
            font-size: 14px;
          }
          &:hover {
            text-decoration: none;
            color: gray;
          }
        }
        .menu-dropdown-items {
          top: 0;
          left: 0;
          font-size: 14px;
          &:before, &:after {
            content: none;
          }
          li {
            line-height: 1.2em;
            padding: 9px 17px;
            cursor: pointer;
            width: 100%;
          }
          li:hover {
            background: #f5f5f5;
          }
          a {
            color: #000;
          }
          a:hover {
            text-decoration: none;
            color: inherit;
          }
        }
        .act-list {
          margin-left: auto;
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
    &__archive {
      h3 {
        color: #333;
        font-size: 24px;
        margin: 0 0 20px;
        font-weight: normal;
        padding-top: 0;
        a {
          font-size: 13px;
          margin-top: 5px;
          color: #0094d9;
          outline: 0;
          margin-left: 10px;
          &:hover {
            color: #00608d;
            text-decoration: underline;
          }
        }
      }
    }

    &__list, &__archive {
      .custom {
        background: #fff;
        border: 1px solid #ddd;
        cursor: pointer;
        margin-bottom: 20px;
        padding: 20px;
        position: relative;
        display: flex;
        align-items: center;
        .action-buttons {
          font-size: 13px;
          margin-left: 10px;
          text-align: right;
          a {
            border: 1px dashed #e4e4e4;
            padding: 10px 7px 10px 3px;
            color: gray;
            text-decoration: none;
            i {
              margin-right: 5px;
            }
            &:hover {
              color: @main-color;
              i {
                color: @main-color;
              }
            }
          }
          a + a {
            margin-left: 15px;
          }
        }
        &:hover {
          border-color: @main-color;
        }
        .name {
          font-size: 18px;
          margin-bottom: 5px;
          word-wrap: break-word;
        }
        .count {
          color: gray;
          font-size: 13px;
        }
        .main-info {
          flex-grow: 1;
        }
        i {
          color: gray;
          font-size: 13px;
        }
        .price {
          min-width: 150px;
          color: #333;
          font-size: 24px;
          font-weight: bold;
          padding-right: 25px;
          text-align: right;
          span {
            white-space: nowrap;
          }
          i {
            color: #333;
            font-size: 22px;
            font-weight: bold;
            margin-left: 5px;
          }
        }
      }
    }

    &__list {
      h3 {
        color: #333;
        font-size: 24px;
        margin: 0 0 20px;
        font-weight: normal;
        padding-top: 0;
        a {
          float: right;
          font-size: 13px;
          margin-top: 5px;
          color: #0094d9;
          outline: 0;
          i {
            margin-right: 5px;
            color: #0094d9;
          }
          &:hover {
            color: #00608d;
            text-decoration: underline;
            i {
              color: #00608D
            }
          }
        }
      }
    }
  }

  @media (max-width: 991px) {
    .account-wishlist {
      background-color: #f6f6f6;
      &__info {
        background-color: #fff;
      }
      &__list, &__archive {
        .custom {
          .action-buttons {
            width: 100px;
            white-space: nowrap;
            a {
              border-radius: 4px;
              border: 1px solid #ddd;
              display: inline-block;
              height: 35px;
              padding: 0;
              text-align: center;
              width: 35px;
              line-height: 35px;
              span {
                display: none;
              }
              i {
                font-size: 16px;
                margin: 0;
              }
            }
          }
        }
      }
    }
  }

  @media (max-width: 767px) {
    .account-wishlist { 
      &__list, &__archive {
        .custom {
          flex-direction: column;
          align-items: flex-start;
          min-height: 120px;
          .action-buttons {
            position: absolute;
            right: 20px;
            top: 20px;
            width: 35px;
            white-space: unset;
            a {
              + a {
                margin-left: 0;
                margin-top: 10px;
              }
            }
          }
          .price {
            text-align: left;
            font-size: 18px;
            margin-top: 10px;
            i {
              font-size: 16px;
            }
          }
          > i {
            display: none;
          }
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
      &__newlist {
        border-radius: 8px;
      }
      &__list, &__archive {
        .custom {
          border-radius: 8px;
          .action-buttons {
            a {
              display: none;
            }
          }
          &:hover {
            .action-buttons {
              a {
                display: inline-block;
              }
            } 
          }
        }
      }
    }
  }

  @media (max-width: 767px) {
    .account-wishlist {
      &__info {
        .main-block {
          display: block;
          width: 100%;
        }
      }
      &__body {
        .action-buttons {
          height: auto;
          flex-direction: column;
          padding-top: 10px;
          padding-bottom: 10px;
          .guest-info {
            max-width: 100%;
          }
          .act-list {
            margin-left: 0;
            width: 100%;
          }
          &.auth.selectable {
            .selection-panel {
              position: fixed;
              bottom: 0;
              left: 0;
              width: 100%;
              padding: 15px 20px;
              border-top: 1px solid #e5e5e5;
              background: #f6f6f6;
              z-index: 1011;
              height: 65px;
              color: #333;
              .m-checkbox {
                label {
                  &:before {
                    border: solid 1px #333;
                  }
                }
              }
              .cancel, .product-counter {
                display: none;
              }
              a.apply {
                border-color: @main-color;
                background-color: @main-color;
                color: #fff;
                font-weight: bold;
                &:not(.disabled):hover {
                  background-color: @hover-color;
                }
              }
            }
          }
          a.clear-wishlist, a.rename, a.archive {
            border-radius: 4px;
            background: #fff;
            border: 1px solid #d8d8d8;
            color: #333;
            text-align: center;
            font-size: 16px;
            width: 100%;
            margin: 10px 0 0;
            display: inline-block;
            line-height: 35px;
          }
          > button {
            width: 100%;
            margin-top: 10px;
          }
          .menu-dropdown {
            width: 100%;
            justify-content: center;
            margin-right: 0;
            order: 1;
            margin-top: 10px;
            border-radius: 4px;
            background: #fff;
            border: 1px solid #d8d8d8;
            line-height: 35px;
            a {
              max-width: unset;
              color: #333;
              &:hover {
                color: #333;
              }
            }
            .menu-dropdown-items {
              width: 100%;
              li {
                line-height: 2em;
                border-bottom: 1px solid #d8d8d8;
                &:last-child {
                  border-bottom: none;
                }
              }
            }
          }
        }
      }
    }  
  }

  @media (min-width:767px) and (max-width: 1199px) {
    .account-wishlist {
      &__body {
        .guest-info {
          max-width: 55%;
          font-size: 13px;
        }
      }
    }
  }

  @media (min-width:992px) and (max-width: 1199px) {
    .account-wishlist {
      &__list, &__archive {
        .custom {
          .action-buttons {
            text-align: left;
            a {
              border: none;
              padding: 0;
              width: 100%;
              + a {
                margin: 10px 0 0;
              }
            }
          }
        }
      }
    }
  }
</style>