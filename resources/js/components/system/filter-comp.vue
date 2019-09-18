<template>
    <div class="ui-collapse ui-collapse_list" :class="{'ui-list-controls_active': activeFiltr}">
      <a @click="onClick" class="ui-link ui-collapse__link_left ui-collapse__link ui-collapse__link_list" :class="{'ui-collapse__link_in': filterOpen}">
        <i class="ui-collapse__icon ui-collapse__icon_left fa" :class="{'fa-chevron-down' : !filterOpen, 'fa-chevron-up': filterOpen}"></i>
        <span class="ui-collapse__link-text">{{item.name}}</span>
      </a>
      <div class="ui-collapse__content ui-collapse__content_list" :class="{'ui-collapse__content_in': filterOpen}">
        <div v-if="item.filter_type=='Число'">
          <div class="ui-input-small ui-input-small_list">
            <span @click="clearMinValue" class="ui-input-small__icon ui-input-small__icon_list" :class="{'ui-input-small__icon_hidden': itemGrp.minValue===''}"><i class="fas fa-times"></i></span>
            <input ref="inp1" @input="onValue($event.target)" :value="itemGrp.minValue" class="ui-input-small__input ui-input-small__input_list" type="text" :placeholder="'от ' + minMaxValue.min">
          </div>
          <div class="ui-input-small ui-input-small_list">
            <span @click="clearMaxValue" class="ui-input-small__icon ui-input-small__icon_list" :class="{'ui-input-small__icon_hidden': itemGrp.maxValue===''}"><i class="fas fa-times"></i></span>
            <input ref="inp2" @input="onValue($event.target)" :value="itemGrp.maxValue" :placeholder="'до ' + minMaxValue.max" class="ui-input-small__input ui-input-small__input_list" type="text">
          </div>
          <div class="ui-radio ui-radio_list">
            <radio-button v-for="it in diapValue" :checked="it.id==curDiap" :key="it.id" :list="true" :name="item.filter_field" :value="it.id" :caption="it.cap" @input="onInput($event)"></radio-button>
          </div>
        </div>
        <div  v-else class="ui-list-controls__content">
          <div v-if="diapValue.length>9" class="ui-input-search ui-input-search_list">
            <input v-model="fSearch" type="text" placeholder="Поиск" class="ui-input-search__input ui-input-search__input_list">
            <span class="ui-input-search__icon ui-input-search__icon_search ui-input-search__icon_list"><i class="fa fa-search"></i></span>
          </div>
          <div class="ui-checkbox-group ui-checkbox-group_list">
            <checkbox-button v-for="it in filterDiap" :key="it.id" :list="true" :value="it.id" :caption="it.cap" @input="onInputCheck($event)" :checked="itemGrp.fChecked.find(el => el==it.id)"></checkbox-button>
          </div>
          <div>
            <a v-if="itemGrp.fChecked.length>0" @click="clearCheck($event)" class="ui-link ui-link_red ui-link_pseudolink ui-list-controls__link ui-list-controls__link_clear">Сбросить</a>
          </div>
        </div>
      </div>
    </div>
</template>

<script>
import { mapGetters } from 'vuex';
import radioButton from './radio-button.vue';
import checkboxButton from './checkbox-button.vue';
    export default {
        data() {
            return {
              fltOpen: null,
              curDiap: 0,
              fSearch: ''
            }
        },
        props: [
          'item'
        ],
        components: {
          'radio-button': radioButton,
          'checkbox-button': checkboxButton
        },
        computed: {
          ...mapGetters([
          ]),
          itemGrp() {
            return this.item.grp_data;
          },
          minVal() {
            return this.itemGrp.minVal;
          },
          maxVal() {
            return this.itemGrp.maxVal;
          },
          minMaxValue() {
            if (this.item.filter_type=='Число' && this.itemGrp) {
              return {
                min: this.itemGrp.min,
                max: this.itemGrp.max
              }
            } else {
              return {}
            }
          },
          activeFiltr() {
            if (this.item.filter_type=='Число') {
              return this.itemGrp.minValue !== '' || this.itemGrp.maxValue !== '';
            } else {
              return this.itemGrp.fChecked.length>0;
            }
          },
          filterOpen() {
            if (this.fltOpen === null) {
              return this.activeFiltr;
            }
            return this.fltOpen;
          },
          diapValue() {
            if (this.item.filter_type=='Число') {
              let d = (this.minMaxValue.max-this.minMaxValue.min)/6;
              let res = [{id: 0, from: this.minMaxValue.min, to: this.minMaxValue.max, cap:'Все'}];
              
              if (this.item.diaps.length) {
                this.item.diaps.forEach((el, ind) => {
                  let st = +el.value1 ? +el.value1 : +this.minMaxValue.min;
                  let end = +el.value2 ? +el.value2 : +this.minMaxValue.max;
                  let cap;
                  if (el.descr1 && el.descr2) {
                    cap = el.descr1 + ' - ' + el.descr2;
                  } else {
                    cap = el.descr1 + el.descr2;
                  }
                  res.push({id: ind+1, from: st, to: end, cap: cap});
                });
              } else {
                let st = +this.minMaxValue.min;
                let st1 = st;
                for (let i = 1; i < 7; i++) {
                  let end = Math.min(st1+Math.ceil(d*i), this.minMaxValue.max);
                  let cap = '' + st + (this.item.mark ? ' ' + this.item.mark : '') + ' - ' + end + (this.item.mark ? ' ' + this.item.mark : '');
                  if (i == 1) {
                    cap = 'Менее ' + end + (this.item.mark ? ' ' + this.item.mark : '');
                  }
                  if (i == 6) {
                    cap = '' + st + (this.item.mark ? ' ' + this.item.mark : '') + ' и более';
                    end = this.minMaxValue.max;
                  }
                  res.push({id: i, from: st, to: end, cap: cap});
                  st = end + 1;
                }
              }
              return res;
            } else {
              if (this.itemGrp &&  this.itemGrp.items) {
                return this.itemGrp.items.map((el, ind) => {
                  return {id: ind, cap: el};
                }); 
              }
              return [];
            }
          },
          filterDiap() {
            if (this.item.filter_type=='Число') {return [];}
            if (this.fSearch == '') {return this.diapValue;}
            let fil = this.fSearch.toUpperCase();
            return this.diapValue.filter((el) => {
              return el.cap.toUpperCase().indexOf(fil) != -1;
            })
          }
        },
        beforeMount() {
          if (!this.itemGrp) {return;}
          if (this.itemGrp.filter_type=='Строка') {return;}
          this.onValue(true);
        },
        watch: {
          '$route' (val) {
            if (!this.itemGrp) {return;}
            if (this.itemGrp.filter_type=='Строка') {return;}
            this.onValue(true);
          }
        },
        methods: {
          onClick() {
            if (this.activeFiltr && this.fltOpen === null) {
              this.fltOpen = false;
            } else {
              this.fltOpen = !this.fltOpen;
            }
          },
          clearCheck(ev) {
            this.fltOpen = true;
            this.$emit('change', ev.target);
            this.itemGrp.fChecked.splice(0);
          },
          onInput(ev) {
            let e = ev.value;
            this.curDiap = e;
            if (e == 0) {
              this.itemGrp.minValue = '';
              this.itemGrp.maxValue = '';
            } else {
              this.itemGrp.minValue = this.diapValue[e].from == this.minMaxValue.min ? '' : this.diapValue[e].from;
              this.itemGrp.maxValue = this.diapValue[e].to == this.minMaxValue.max ? '' : this.diapValue[e].to;
            }
            this.$emit('change', ev);
          },
          onInputCheck(ev) {
            let e = ev.value
            let ind = this.itemGrp.fChecked.indexOf(e);
            if (ind==-1) {
              this.itemGrp.fChecked.push(e); 
            } else {
              this.itemGrp.fChecked.splice(ind, 1);
            }
            this.$emit('change', ev);
          },
          clearMinValue() {
            this.itemGrp.minValue = '';
            this.onValue(this.$refs.inp1);
          },
          clearMaxValue() {
            this.itemGrp.maxValue = '';
            this.onValue(this.$refs.inp2);
          },
          onValue(fromRoute) {
            if (fromRoute !== true) {
              if (this.$refs.inp2 == fromRoute) {
                if (this.itemGrp.maxValue == fromRoute.value) {
                  return;
                }
                this.itemGrp.maxValue = fromRoute.value;
              } else if (this.$refs.inp1 == fromRoute) {
                if (this.itemGrp.minValue == fromRoute.value) {
                  return;
                }
                this.itemGrp.minValue = fromRoute.value;
              }
            }
            let from;
            if (this.itemGrp.minValue === '') {
              from = this.minMaxValue.min;
            } else {
              from = +this.itemGrp.minValue;
            }
            let to;
            if (this.itemGrp.maxValue === '') {
              to = this.minMaxValue.max;
            } else {
              to = +this.itemGrp.maxValue;
            }
            let it = this.diapValue.find((el) => {
              if (el.id == 0) {
                return false;
              }
              return (el.from == from) && (el.to == to);
            })
            if (it) {
              this.curDiap = it.id;
            } else {
              this.curDiap = 0;
            }
            if (fromRoute !== true) {
              this.$emit('change', fromRoute); 
            }
          }
        }
    }
</script>

<style>
  .ui-collapse_list {
    background: #fff;
    min-width: 200px;
  }
  .ui-collapse {
    display: block !important;
  }
  .ui-collapse__link_left {
    font-size: 16px;
    color: #4e4e4e;
  }
  .ui-collapse__link_list {
    display: block;
    padding: 8px 0 8px 13px;
  }
  .ui-collapse__link_left.ui-collapse__link_in {
    font-weight: bold;
  }
  .ui-collapse__link-text {
    display: inline-block;
    width: calc(100% - 30px);
  }

  .ui-collapse__link_list:before {
    content: '';
    display: inline-block;
    vertical-align: middle;
    height: 100%;
    min-height: 33px;
  } 
  .ui-collapse__icon {
    padding-left: 5px;
  }
  .ui-collapse__icon_left {
    color: #afafaf;
    display: inline-block;
    padding-left: 0;
    font-size: 16px;
    vertical-align: top;
    margin-top: 8px;
    margin-right: 5px;
    height: 30px;
  }
  .ui-collapse__icon_left:before {
    vertical-align: top;
    display: inline-block;
    line-height: 1;
  }
  .ui-collapse__link_left:hover {
    color: #1D71B8;
  }
  .ui-collapse__link_list:hover {
    background-color: #E8F2FB;
  }
  .ui-collapse__link_list:hover i {
    color: #1D71B8;
  }
  .ui-collapse__content {
    margin-top: 10px;
    max-height: 0;
    opacity: 0;
    overflow: hidden;
  }
  .ui-collapse__content_list {
    margin-top: 0;
  }
  .ui-collapse__content_in {
    max-height: 3000px;
    opacity: 1;
    overflow: visible;
    transition: max-height 1s, opacity .3s;
  }
  .ui-collapse__content_list.ui-collapse__content_in {
    padding-bottom: 35px;
  }
  .ui-input-small {
    position: relative;
    background: #fff;
    border: 1px solid #d9d9d9;
    border-radius: 8px;
    height: 40px;
    font-size: 14px;
  }
  .ui-input-small_list {
    display: inline-block;
    margin: 5px;
    width: 42%;
  }
  .ui-input-small_list:first-child {
    margin-left: 15px;
  }

  .ui-input-small__icon {
    color: #8c8c8c;
    position: absolute;
    right: 7px;
    top: 8px;
    font-size: 16px;
    cursor: pointer;
  }
  .ui-input-small__icon_hidden {
    display: none;
  }
  .ui-input-small__input {
    color: #333;
    padding-left: 12px;
    height: 95%;
    border-radius: 8px;
    border: none;
    outline: none;
    width: calc(100% - 12px);
  }
  .ui-input-small__icon:hover {
    color: #333;
  }
  .ui-radio {
    visibility: visible !important;
    color: #333;
  }
  .ui-radio input[type="radio"] {
    opacity: 0;
    margin: 0 0 0 -9999px;
    position: absolute;
    top: 50%;
    height: 0;
  }
  .ui-list-controls_active .ui-collapse__link_list {
    color: #1D71B8;
  }
  .ui-checkbox-group_list {
    max-height: 298px;
    overflow-x: hidden;
    overflow-y: auto;
  }
  .ui-list-controls__content:after {
    clear: both;
    content: '';
    display: block;
  }
  .ui-list-controls__link {
    font-size: 13px;
    margin: 5px 0 8px;
  }
  .ui-list-controls__link_clear {
    float: right;
    margin-right: 16px;
    margin-bottom: -30px;
    padding-top: 5px;
  }
  .ui-input-search_list {
    margin: 5px 15px 8px;
  }
  @media (min-width: 992px) {
  .left-filters__list .ui-collapse_list:first-child .ui-collapse__link {
      -moz-border-radius-topleft: 8px;
      -webkit-border-top-left-radius: 8px;
      border-top-left-radius: 8px;
      -moz-border-radius-topright: 8px;
      -webkit-border-top-right-radius: 8px;
      border-top-right-radius: 8px;
    }
  }
</style>