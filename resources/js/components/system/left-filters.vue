<template>
    <div class="left-filters" :class="{'active': fl_showed}">
      <div class="left-filters__mobile-header">
        <span>Фильтры</span>
        <i class="fas fa-times" @click="$emit('close_filtr')"></i>
      </div>
      <div class="left-filters__offers">
        <product-offers @close_filtr="$emit('close_filtr')"></product-offers>
      </div>
      <leftfilter-picked @close_filtr="$emit('close_filtr')"></leftfilter-picked>
      <div class="left-filters__list">
        <filter-component v-for="el in filterItems" :key="el.id"
        :item="el" @change="onChange"></filter-component>
      </div>
      <div class="left-filters__buttons">
        <div class="left-filters__buttons-main">
          <button class="button-ui button-ui_brand left-filters__button" @click="clickUse">Применить</button>
          <button class="button-ui button-ui_white left-filters__button" @click="clickRem">Сбросить</button>
        </div>
        <router-link class="left-filters__button left-filters__button_extended-link ui-link ui-link_blue" :to="filtrPath">Все фильтры <i class="fas fa-long-arrow-alt-right"></i></router-link>
      </div>
      <div v-if="floatBtn" class="apply-filters-float-btn" :style="{'top': top+'px'}" @click="clickUseFloat"></div>
    </div>
</template>

<script>
import { mapGetters } from 'vuex';
import filterComp from './filter-comp.vue'; 
import leftFilterPicked from '../pages/product/leftfilters_picked.vue';
import productOffers from '../pages/product/product-offers.vue';
    export default {
        data() {
            return {
              floatBtn: false,
              timeInt: null,
              top: 0
            }
        },
        props: [
          'fl_showed'
        ],
        computed: {
          ...mapGetters([
            'filterItems',
            'categoryFilters',
            'getScreenState'
          ]),
          filtrPath() {
            return {
              path: this.$router.currentRoute.path+'/filters',
              query: this.categoryFilters
            }
          }
        },
        components: {
          'filter-component': filterComp,
          'leftfilter-picked': leftFilterPicked,
          'product-offers': productOffers
        },

        methods: {
          onChange(ev) {
            this.top = ev.parentElement.offsetTop - 16 - ev.parentElement.parentElement.scrollTop;
            if (this.timeInt) {
              clearInterval(this.timeInt);
            }
            this.floatBtn = true;
            this.timeInt = setTimeout(() => {
              this.timeInt = null;
              this.floatBtn = false;
            }, 4000);
          },
          clickUseFloat() {
            if (this.timeInt) {
              clearInterval(this.timeInt);
            }
            this.timeInt = null;
            this.floatBtn = false;
            this.clickUse();
          },
          clickUse() {
            this.$emit('close_filtr');
            if (this.$router.currentRoute) {
              let obj = {};
              Object.assign(obj, this.categoryFilters);
              this.filterItems.forEach((el) => {
                if (el.filter_type=="Число") {
                  if (el.grp_data.minValue !== '' || el.grp_data.maxValue !== '') {
                    obj['f['+el.id_1s+']'] = ''+(el.grp_data.minValue==='' ? el.grp_data.min : el.grp_data.minValue)+'-'
                      +(el.grp_data.maxValue==='' ? el.grp_data.max : el.grp_data.maxValue);
                  } else {
                    delete obj['f['+el.id_1s+']'];
                  }
                } else {
                  if (el.grp_data.fChecked && el.grp_data.fChecked.length) {
                    obj['f['+el.id_1s+']'] = el.grp_data.fChecked.join('-');
                  } else {
                    delete obj['f['+el.id_1s+']'];
                  }
                }
              });
              this.$router.push({
                path: this.$router.currentRoute.path,
                query: obj
              });
              let scrTop = this.getScreenState == 1 ? 40 : 80;
              window.scrollTo({ top: scrTop, behavior: 'smooth' });
            }
          },
          clickRem() {
            this.$emit('close_filtr');
            if (this.$router.currentRoute) {
              let obj = {};
              Object.assign(obj, this.categoryFilters);
              this.filterItems.forEach((el) => {
                delete obj['f['+el.id_1s+']'];
              });
              this.$router.push({
                  path: this.$router.currentRoute.path,
                  query: obj
              });
              let scrTop = this.getScreenState == 1 ? 40 : 80;
              window.scrollTo({ top: scrTop, behavior: 'smooth' });
            }
          }
        }
    }
</script>

<style>
  .left-filters {
    position: relative;
    border-radius: 8px;
    box-shadow: 0 1px 2px 0 rgba(0,0,0,0.16);
    border: solid 1px transparent;
    background: #fff;
  }
  .left-filters__buttons {
    display: flex;
    flex-direction: column;
    align-items: center;
    margin: 20px 0;
  }
  .left-filters__buttons-main {
    width: 100%;
  }
  .left-filters__button {
    display: block;
    width: 90%;
    margin: 0 auto 8px;
    text-align: center;
  }

  .left-filters .apply-filters-float-btn {
 border-radius:8px;
 width:120px;
 padding:0;
 font-size:16px;
 line-height:64px;
 height:64px;
 border:none;
 outline:none;
 cursor:pointer;
 text-align:center;
 color:#fff;
 background-image:linear-gradient(to top, #1D71B8, #2288DB);
 font-weight:bold;
 position:absolute;
 right:-120px;
 z-index:110
}
.left-filters .apply-filters-float-btn:before {
 display:block;
 content:'';
 position:absolute;
 left:-5px;
 top:10px;
 background-image:linear-gradient(45deg, #1D71B8, #2288DB);
 width:45px;
 height:45px;
 border-radius:4px;
 transform:rotate(135deg);
 z-index:0
}
.left-filters .apply-filters-float-btn:after {
 border-radius:8px;
 box-shadow:inherit;
 display:block;
 content:'Показать';
 position:absolute;
 left:0;
 top:0;
 background-image:inherit;
 border-radius:inherit;
 width:100%;
 height:100%;
 z-index:1
}
.left-filters .apply-filters-float-btn:hover {
 background-image:linear-gradient(to bottom, #5BA8E6, #175C95);
 box-shadow:inset 0 -2px 0 0 rgba(0,0,0,0.2)
}
.left-filters .apply-filters-float-btn:hover:before {
 background-image:linear-gradient(45deg, #5BA8E6, #175C95);
 box-shadow:inset -2px 0 0 0 rgba(0,0,0,0.2)
}
.left-filters__mobile-header {
    display: none;
}
.left-filters__offers {
  display: none;
}
.left-filters__button_extended-link {
    margin-top: 12px;
    padding-right: 22px;
}
.left-filters__button_extended-link i {
  position: relative;
}

.left-filters__button_extended-link i:before {
    position: absolute;
    top: -12px;
    left: 6px;
}
@media (max-width: 991px) {
.left-filters {
    display: block;
    position: fixed;
    border-radius: 0;
    top: 0;
    right: -75%;
    z-index: 1100;
    width: 75%;
    background: #fff;
    overflow-y: auto;
    height: 100%;
    padding-top: 60px;
  }
  .left-filters.active {
    right: 0;
  }
  .left-filters.active {
    right: 0;
  }
  .left-filters__mobile-header {
    display: block;
    position: fixed;
    top: 0;
    width: 75%;
    height: 60px;
    z-index: 1000;
    padding: 20px;
    background: #fff;
    box-shadow: 0 3px 4px 0 rgba(0,0,0,0.2);
  }
  .left-filters__mobile-header i {
    position: absolute;
    top: 20px;
    right: 29px;
    font-size: 18px;
    cursor: pointer;
  }
  .left-filters__buttons-main {
    display: none;
    flex-direction: row-reverse;
    height: 66px;
    position: fixed;
    bottom: 0;
    background: #fff;
    width: 75%;
    margin: 0;
    padding: 11px;
    z-index: 1000;
    box-shadow: 0 -4px 8px 0 rgba(0,0,0,0.16);
  }
  .left-filters.active .left-filters__buttons-main {
    right: 0;
    display: flex;
  }
  .left-filters__button {
    flex-grow: 1;
    margin: 0 8px;
  }
  .apply-filters-float-btn {
    display: none;
  }
  .left-filters__offers {
    display: block;
  }
  .left-filters__buttons {
    margin: 33px 0;
  }
  .left-filters__button_extended-link {
    margin-bottom: 70px;
    border-radius: 8px;
    height: 40px;
    padding: 8px 20px;
    font-size: 16px;
    outline: none;
    background: #fff;
    color: #333 !important;
    border: 1px solid #d9d9d9;
    cursor: pointer;
    width: 80%;
  }
  .left-filters__button_extended-link i {
    display: none;
  }
}
@media (max-width: 767px) {
  .left-filters.active, .left-filters__buttons-main, .left-filters__mobile-header {
    width: 100%;
  }
}
</style>