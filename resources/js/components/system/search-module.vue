<template>
    <div class="search-module">
       <div class="search-module__container">
         <div class="wrap">
           <div class="search-module__content">
             <ul class="tabs">
               <li @click="curPage=0" class="found" :class="{current: curPage==0}">Поиск по наименованию</li>
               <li @click="curPage=1" class="found-res" :class="{current: curPage==1}">Экcпресс поиск резисторов и конденсаторов</li>
             </ul>
             <a @click="$emit('close')" class="close"><span>закрыть</span></a>
             <section v-show="curPage==0" class="search-module__search-name">
               <form @submit.prevent="onSubmit">
                 <input ref="searchInput" v-model="searchValue" type="text" placeholder="введите наименование" autocomplete="off">
                 <button type="submit" class="ico-search">&nbsp;</button>
                 <span v-show="!inLoad && valLength>1 && products.totalQty" class="total-text">Всего найдено: {{products.totalQty}}</span>
               </form>
               <div v-show="valLength>1" class="search-module__search-result" :class="{load: inLoad}">
                 <div class="loader"><img src="/image/catalog/wait.gif" width="200"></div>
                 <div v-show="products.totalQty === 0" class="no-result">
                   Ничего не найдено
                 </div>
                 <div v-show="products.totalQty" class="table-result">
                   <div v-for="it in products.items" class="table-item">
                     <div v-show="!notBuy" class="m-checkbox">
                        <input v-model="checkList" :id="'sch-'+it.id" :value="it.id" type="checkbox">
                        <label :for="'sch-'+it.id"><i class="fa fa-check"></i></label>
                     </div>
                     <img :src="it.image">

                     <div class="search-module__item-name">
                       <div class="caption">
                          <router-link :to="'/product/'+it.chpu">{{it.name}}</router-link>
                        </div>
                        <div class="price"><span>от {{it.price_with_discount}}</span><i class="fa fa-rub"></i></div>
                        <product-rating></product-rating>
                    </div>
                    <div class="search-module__product-controls">
                      <add-in-list-button v-show="!notCompare"
                        v-tooltip.bottom="compare.items.indexOf(it.id) != -1 ? 'Удалить из сравнения' : 'Добавить в сравнение'"
                        :item="it"
                        :list="compare.items"
                        :authOnly="false"
                        delLocalAction="delFromLocalCompare"
                        addLocalAction="addToLocalCompare"
                        icon="fa-bar-chart"
                      ></add-in-list-button>
                      <add-in-list-button
                        v-tooltip.bottom="isInWishAll(it) ? 'Удалить из избранного' : 'Добавить в избранное'"
                        :item="it"
                        :list="wishlist.items"
                        :authOnly="true"
                        :isWish="true"
                        delLocalAction="delFromLocalWishlist"
                        addLocalAction="addToLocalWishlist"
                        addAuthAction="addToServerWishlist"
                        delAuthAction="delFromServerWishlist"
                        icon="fa-heart-o"
                      ></add-in-list-button>
                      <buy-button
                        v-tooltip.bottom="isInCartAll(it) ? 'Перейти в корзину'  : 'Добавить в корзину'"
                        :item="it"
                        :list="cart.items"
                        :small="true"
                        :fromCart="fromCart"
                        @close="$emit('close')"
                      ></buy-button>
                    </div>
                   </div>
                 </div>
                 <div v-show="products.totalQty" class="search-module__paginator">
                   <button 
                    @click="onSelect" 
                    class="button-ui button-ui_brand"
                    :disabled="!checkList.length"
                   >Добавить</button>
                   <paginator
                    @changePage="changePage($event)"
                    :itemQty="products.totalQty"
                    :numPage="page+1"
                    :qtyOnPage="10"
                   ></paginator>
                 </div>
               </div>
             </section>
             <section v-show="curPage==1" class="search-module__search-res"></section>
           </div>
         </div>
       </div>
    </div>
</template>

<script>
  import { mapGetters, mapActions } from 'vuex';
  import productRating from '../pages/product/product-rating.vue';
  import buyButton from './buy-button.vue';
  import addInListButton from './add-in-list-button.vue';
  import paginator from './paginator.vue';
    export default {
        data() {
            return {
              curPage: 0,
              inLoad: false,
              searchValue: '',
              searchInterval: undefined,
              page: 0,
              products: {},
              checkList: []
            }
        },
        computed: {
          ...mapGetters([
            'wishlist',
            'compare',
            'cart',
            'isInCartAll',
            'isInWishAll'
          ]),
          valLength() {
            return this.searchValue.length;
          }
        },
        props: [
          'notCompare',
          'notBuy',
          'fromCart'
        ],
        methods: {
          ...mapActions([
            'queryForApp',
            'addToLocalCompare'
          ]),
          seachProducts(val) {
            if (this.valLength<=1) {
              return;
            }
            this.inLoad = true;
            this.queryForApp({
              notcatch: false,
              url: '/api/products/quickseach',
              successAction: this.loadProduct,
              errorAction: (data) => {
                this.inLoad = false;
              },
              params: {
                value: val,
                page: this.page,
                perPage: 10
              }
            });
          },
          onSubmit() {
            this.seachProducts(this.searchValue);
          },
          loadProduct(data) {
            this.inLoad = false;
            this.products = data.products;
            this.checkList = [];
          },
          changePage(e) {
            this.page = e-1;
            this.seachProducts(this.searchValue);
          },
          onSelect() {
            if (this.checkList.length) {
              this.addToLocalCompare(this.checkList);
              this.inLoad = true;
              this.queryForApp({
                url: '/api/products/compare',
                notcatch: false,
                errorAction: () => {
                  this.inLoad = false;
                },
                params: {
                  pr: this.compare.items
                }
              }).then(() => {
                  this.checkList = [];
                  this.inLoad = false;
                  $notify("alert", 'Товары успешно добавлены', "success");  
              });
            }
          }
        },
        components: {
          productRating,
          buyButton,
          addInListButton,
          paginator
         
        },
        watch: {
          searchValue(val) {
            if (this.searchInterval) {
              clearInterval(this.searchInterval);
              this.searchInterval = undefined;
            }
            this.searchInterval = setTimeout(() => {
              this.seachProducts(val);
            }, 300);
          }
        },
        mounted() {
          if (this.$refs['searchInput']) {
            this.$refs['searchInput'].focus();
          }
        }
    }
</script>

<style lang="less">
@import '../../../less/vars.less';
  .search-module {
    position: absolute;
    width: 100%;
    bottom: 20px;
    &__paginator {
      margin-top: 30px;
      button {
        height: 49px;
        display: block;
        float: left;
        padding: 10px 10px;
      }
      .pagination-container {
        margin-left: 105px;
        margin-right: 10px;
      }
    }
    &__product-controls {
      display: flex;
      button {
        margin-right: 5px;
      }
    }
    &__item-name {
      padding: 0 20px;
      display: flex;
      align-items: center;
      flex-grow: 1;
      flex-wrap: wrap;
      .caption {
        width: 60%;
        margin-right: 30px;
        a:hover {
          text-decoration: none;
          color: tomato;
        }
      }
      .price {
        color: #333;
        font-weight: bold;
        font-size: 22px;
        width: calc(40% - 30px);
        white-space: nowrap; 
        span {
          margin-right: 10px;
        }
        i {
          font-size: 20px;
          color: #b2b2b2;
        }
      }
    }
    &__search-result {
      .loader {
        display: none;
        text-align: center;
      }
      .no-result {
        background-image: url("data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAwAAAAKCAYAAACALL/6AAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAJ5JREFUeNpiYMACQkND34MwNjkWBuxAAIc4AxMy53+yewG6AnQxJiQJkKn1UO4HKAaBeqgchg3zkRRdgGKY5vkoGoAmOACpACAuxOJskFgAVA0DE9Q6kAkbGOfu3ICuGioGwvNBakGhVAANFbjpq1evdsRiy3mQWiaoR0EmKMCsBcbBfxBGcq4CVE09yIYHQJwAxWBXoJm+H4n9ACDAAP37NUZoP0gjAAAAAElFTkSuQmCC");
        background-repeat: no-repeat;
        background-position: 0 50%;
        padding-left: 20px;
        margin: 0 0 1em 2em;
      }
      .table-item {
        display: flex;
        align-items: center;
        padding: 5px;
        &:nth-child(odd) {
          background-color: #f5f5f5;
        }
        .m-checkbox input[type="checkbox"]:checked + label i {
          line-height: 20px;
        }
      }
      &.load {
        .loader {
          display: block;
        }
        .table-result, .no-result {
          display: none;
        }
      }
    }
    &__content {
      .tabs {
        position: absolute;
        top: -2px;
        left: 0;
        width: 100%;
        background-color: @main-color;
        border-bottom: 2px solid @main-color;
        padding: 0;
        box-sizing: border-box;
        font-size: 14px;
        margin: 0 0 2em;
        li {
          background-repeat: no-repeat;
          display: inline-block;
          line-height: 14px;
          text-align: center;
          vertical-align: bottom;
          background-position: 10px 50%;
          cursor: pointer;
          border-top-right-radius: 5px;
          border-top-left-radius: 5px;
          margin-bottom: -2px;
          border: 2px solid transparent;
          color: #fff;
          font-size: 13px;
          padding: 8px 15px 6px 26px;
          &.current {
            background-color: #fff;
            border: 2px solid @main-color;
            border-bottom: 2px solid #fff;
            color: #333;
          }
          &:hover {
            background-color: #edf2f8;
            border: 2px solid @main-color;
            border-bottom: 2px solid #edf2f8;
            color: #333;
          }
          &:first-child {
            border-left: none;
          }
          &.found {
            background-image: url("data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAoAAAAKCAYAAACNMs+9AAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAHNJREFUeNpi+P//PwMQT/iPCiZAxeEYpugDECcAsQMQF0D5E9AV/odKIptQABWHizExQMABBlRwAUorwARgCh3QFBpA6QdwESQ3gqwzgNJfgHgzuhux+XozlE5AV4gNJyArxqcQWXEAI1g1fhAAxB8AAgwA8uXwwGtw3isAAAAASUVORK5CYII=");
          }
          &.found-res {
            background-image: url("data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAkAAAAKCAYAAABmBXS+AAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAExJREFUeNqMUFsKACAIc9H9z1AnXdgDhlS4H1HnpoKkCTyBxIliCTip7UmG3qk1cPnhY8e0nQIh5hevYVG7KTupCwGX6zrkmU+lIcAAPBoaigBMNUUAAAAASUVORK5CYII=");
          }
          &.found.current, &.found:hover {
            background-image: url("data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAoAAAAKCAYAAACNMs+9AAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAItJREFUeNpiYQCCWWdT+oFUAQMCTEgznlOIxGdggipKAOJEIHYEYpCCBKg4HLBATSoEmrAAKnYAqAhE90M1QUyESTKgggtQJymgK3RAU2gAIoC2PEC2egIQ10OtOwDVVA/EG5B1MuLwNUhRAMiDMLczMuAAQM2gkJgPU4xTIZriQLwKoYpBTvgAEGAAs4EuDnOrcc8AAAAASUVORK5CYII=");
          }
          &.found-res.current, &.found-res:hover {
            background-image: url("data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAkAAAAKCAYAAABmBXS+AAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAElJREFUeNpiYEACs86m/EemYYCJgQjAAtT1HkgLoEsgmfYBxWhs1oHYRFmH4Uhs8sQ5HJ+JacZzGEE0I7LvYILovmMkxiSAAAMAaIcsn5IXWjAAAAAASUVORK5CYII=");
          }
        }
      }
      .close {
        top: 0;
        bottom: auto;
        right: 4px;
        padding: 0 30px 0 3px;
        color: #fff;
        position: absolute;
        font-size: 14px;
        height: 26px;
        line-height: 26px;
        display: inline-block;
        background-image: url("data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABoAAAAaCAYAAACpSkzOAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAX5JREFUeNrElrFxhDAQRU8wJE6IrwFnTrALgBwXcBW4ADdAAy7AFVCAKUABoWEudgMXkzhhNHh3LDS6tVbIZw5rZgdGLPvQ35WW3W6jIZYcpmnK4YJ2D7Ynj09gHZgUQsiLQADAwJUjODcQWgGwCwYB5BkuhwtVqgH2sggCCK6i/GNKGoBV9kTkWEm5Qu5LHesnSOfkTK5xHGO0paiM30HHPJcOJt/sxOOLbdve4X2WZR9pmn66IMMw3PR9f4v3RVEcaYGAhI9mRbqEndWllIoxEAbkIOjDLHavYxvpcuqRJInClcRxrFwwG4I+6MvAciMdlY37ajsgneOkneWbQe++ZFPYLGkA5LsQhHiIQmoVA9ky/gbi3EfXHEEgKh1XICGgUygE5fJVI3PYGlAXCsGc0JwtwDobJF3Hiq+EXTAGJA1INy2nfL7qsmGePSTpWYcH4OvKxfY0N8LI2lQ4Ua8Iqe1uG5EdjJ2xWQHS0C77f61805+Ta/xubTa+BBgAiuUwJ0Bp5igAAAAASUVORK5CYII=");
        background-position: 100% 50%;
        background-repeat: no-repeat;
        &:hover {
          text-decoration: none;
          background-image: url("data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABoAAAAaCAYAAACpSkzOAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAc9JREFUeNrElrFKA0EQhueClfoKgaCFnWApNrEQy9hpp8FCS0UUn0ERU2ohZ6mdKcXCNGIp2FlEAnmFmFbnv73dm1t3b4+A5w+bI3ez/5fZncweUUWKQgHfu+sbfGnxaPJoWI8HPHo8utHN48NEIAbAOHaY+wRom4G90iCGXPLlwNyozxMtLRMtLOYDP96J3l6Jhn15t8OwwyCIIchixwC29n4DbAF4dy2Btwxre0G5TFbWiDb3iaZnyi3c+Ivo/oro5cmZWWTtybOBtI/UZCgEk3HxhYSt6j2rifDYLBcyweTTbTWGn34Inuk4CHPhIT01KC1hVV3YE5nBeER0fuKG4R6eIUYLc+Gh1Ei9TUYtk43eeEw4PuPrrBsmIYhBrBY8sqxaEtRMPlHCUvU5N8wFQaxU5pV4T+kUzS+xpWHaGFe9pD5I3qthF4NfdmYhiEO1qppqOZC9J74CKQEamFYSgmC5iqpRtiXhrUGq46JBhiDYE181SmVeuc7QVcb97JegMxSVsAsms8kabNeA0kNLLR+6sO5dyR+3oLokTPY9eKTLpg/E4qY6iUJNNb3RSb4gEBNkZmWOiTykI0/b/zn4Kj3KK305+YvXrcr0I8AAPxoHos1qOXAAAAAASUVORK5CYII=");
        }
      }
    }
    &__container {
      border: none;
      position: absolute;
      top: 100%;
      width: 100%;
      min-height: 126px;
      z-index: 20;
      text-align: left;
      white-space: normal;
      background: #fff;
      margin-top: 10px;
      background-color: transparent;
      .wrap {
        border-radius: 7px;
        padding: 42px 10px 10px;
        min-height: 200px;
        position: relative;
        border: 4px solid @main-color;
        background-color: #fff;
        &:before {
          position: absolute;
          z-index: 60;
          left: 24px;
          top: -24px;
          content: "";
          height: 0;
          width: 0;
          pointer-events: none;
          border: 10px solid transparent;
          border-bottom: 10px solid @main-color;
        }
      }
    }
    &__search-name {
      form {
        margin: 1em 0 2em;
        padding: 0 10px;
        white-space: nowrap;
      }
      input {
        border: none;
        border-bottom: 1px solid #1d71b8;
        padding: 3px 4px;
        width: 226px;
        margin-right: 5px;
        outline: none;
      }
      form button {
        border: none;
        width: 26px;
        height: 26px;
        outline: none;
        margin-right: 2em;
        cursor: pointer;
        background: url("data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABoAAAAaCAYAAACpSkzOAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAUBJREFUeNrkVcENwjAMbBEDsABS2YARyo8nI8AEJRNUnaB0AmAC+uRXRmCESCzQEbClixSFQJomfMCSZaWRfTknvibJX9pcXGfkWUiN9EPxnEJBvjG2WvLmUa9vwUAEcqSwdeQeCEyMBiKQmsIeS0leIbJx+0pEthOB7byB0K5OFSEXVKh3MF4NaePUWBeKieOk3LIczDjHCTQx1uriq09JYFkZOV5AiXY3LpM+r24Sac6ysUBDhjPTWil9gVrEktXAkVsaOV6vrsHl8ml5nnY2OcJepuV8fWB7zNF9rATpYO+MQWaIPEdnAmyjiyoAOoA59S/1eL69KUf0vYNC6GbVvzRwfpjtxbL1AhY0sLiTk2VrS4fYR2Pk+H9xmxeq3VEkCG0ymfEjWUbVOg1MgIliJKO2zqIcS/zTZPKz9hRgAOZBeTgMFOUuAAAAAElFTkSuQmCC") no-repeat 50% 50%;
        &:hover {
          background: url("data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABoAAAAaCAYAAACpSkzOAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAT1JREFUeNrkVdENgjAQBeMAjIAbOAJ++esIGAcAJiBMgAxgwAnk1y8cwRE6AhvoXbwmzYmU0sbEeMnlUpr29b32Hp73l/E4bAPI0GYPf2TzCEoCuWNTLWTln643ayAAqaHEmrVHAMtmAwFICSWloYAsqGKgfDlVjAbA9sZAJFcnN4HMYKNew3gzRcYlGyeSieakKFlEzHCNFmjBxvLii1EZXiwLtsYIyFPuRhfC5NUtHPVZOBdoSnOGipTCFKilmqMbaNbmbI0RUKWcthyxo1phVH2jYXvqo/tcC1LBPgWCBFSxj84A2Do3VQLoCEzrf77B8+25HcH3jhxCjUH/8y37B9leBqbewKwalu6kGZiK4RCpM0aa/xfKvJJyO7Egkokzw0eydup1ClhGTCQj4VQ67hzEREzxwN+NpwADAIXkej7JkP87AAAAAElFTkSuQmCC") no-repeat 50% 50%;
        }
      }
    }
  }

  @media (max-width: 767px) {
    .search-module__item-name {
      flex-direction: column;
      align-items: unset;
      .caption {
        width: unset;
      }
      .price {
        font-size: 16px;
        span {
          margin-right: 7px;
        }
        i {
          font-size: 15px;
        }
      }
    }
    .search-module__content {
      .close {
        span {
          display: none;
        }
      }
      .tabs {
        display: flex;
      }
    }
  }
</style>