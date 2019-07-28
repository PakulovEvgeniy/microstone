<template>
    <div class="left-filters">
      <div class="left-filters__list">
        <filter-component v-for="el in filterItems" :key="el.id"
        :item="el" @change="onChange"></filter-component>
      </div>
      <div class="left-filters__buttons">
        <div class="left-filters__buttons-main">
          <button class="button-ui button-ui_brand left-filters__button" @click="clickUse">Применить</button>
          <button class="button-ui button-ui_white left-filters__button" @click="clickRem">Сбросить</button>
        </div>
      </div>
      <div v-if="floatBtn" class="apply-filters-float-btn" :style="{'top': top+'px'}" @click="clickUseFloat"></div>
    </div>
</template>

<script>
import { mapGetters } from 'vuex';
import filterComp from './filter-comp.vue'; 
    export default {
        data() {
            return {
              floatBtn: false,
              timeInt: null,
              top: 0
            }
        },
        computed: {
          ...mapGetters([
            'filterItems',
            'categoryFilters',
            'getScreenState'
          ])
        },
        components: {
          'filter-component': filterComp
        },

        methods: {
          onChange(ev) {
            console.log(ev);
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
 background-image:linear-gradient(to top, #fc8507, #ffa218);
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
 background-image:linear-gradient(45deg, #fc8507, #ffa218);
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
 background-image:linear-gradient(to bottom, #ffbc0b, #ff7400);
 box-shadow:inset 0 -2px 0 0 rgba(0,0,0,0.2)
}
.left-filters .apply-filters-float-btn:hover:before {
 background-image:linear-gradient(45deg, #ffbc0b, #ff7400);
 box-shadow:inset -2px 0 0 0 rgba(0,0,0,0.2)
}
</style>