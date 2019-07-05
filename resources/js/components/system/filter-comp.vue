<template>
    <div class="ui-collapse ui-collapse_list" :class="{'ui-list-controls_active': activeFiltr}">
      <a @click="onClick" class="ui-link ui-collapse__link_left ui-collapse__link ui-collapse__link_list" :class="{'ui-collapse__link_in': filterOpen}">
        <i class="ui-collapse__icon ui-collapse__icon_left fa" :class="{'fa-chevron-down' : !filterOpen, 'fa-chevron-up': filterOpen}"></i>
        <span class="ui-collapse__link-text">{{item.name}}</span>
      </a>
      <div class="ui-collapse__content ui-collapse__content_list" :class="{'ui-collapse__content_in': filterOpen}">
        <div v-if="item.filter_type=='Число'">
          <div class="ui-input-small ui-input-small_list">
            <span @click="minValue=''" class="ui-input-small__icon ui-input-small__icon_list" :class="{'ui-input-small__icon_hidden': minValue===''}"><i class="fas fa-times"></i></span>
            <input v-model="minValue" class="ui-input-small__input ui-input-small__input_list" type="text" :placeholder="'от ' + minMaxValue.min">
          </div>
          <div class="ui-input-small ui-input-small_list">
            <span @click="maxValue=''" class="ui-input-small__icon ui-input-small__icon_list" :class="{'ui-input-small__icon_hidden': maxValue===''}"><i class="fas fa-times"></i></span>
            <input v-model="maxValue" :placeholder="'до ' + minMaxValue.max" class="ui-input-small__input ui-input-small__input_list" type="text">
          </div>
          <div class="ui-radio ui-radio_list">
            <radio-button v-for="it in diapValue" :checked="it.id==curDiap" :key="it.id" :list="true" :name="item.filter_field" :value="it.id" :caption="it.cap" @input="onInput($event)"></radio-button>
          </div>
        </div>
        <div v-else>
          Строка
        </div>
      </div>
    </div>
</template>

<script>
import { mapGetters } from 'vuex';
import radioButton from './radio-button.vue';
    export default {
        data() {
            return {
              filterOpen: false,
              minValue: '',
              maxValue: '',
              curDiap: 0
            }
        },
        props: [
          'item'
        ],
        components: {
          'radio-button': radioButton
        },
        computed: {
          ...mapGetters([
            'productsOfCategory'
          ]),
          minMaxValue() {
            if (this.item.filter_type=='Число') {
              let min = Number.MAX_VALUE;
              let max = 0;
              this.productsOfCategory.forEach((el) => {
                if (this.item.filter_field) {
                  if (+el[this.item.filter_field] > max) {
                    max = +el[this.item.filter_field];
                  }
                  if (+el[this.item.filter_field] < min) {
                    min = +el[this.item.filter_field];
                  }
                }
              })
              if (max==0) {
                min = 0;
                max = 1000;
              }
              return {
                min: min,
                max: max
              }
            } else {
              return {}
            }
          },
          activeFiltr() {
            if (this.item.filter_type=='Число') {
              return this.curDiap>0;
            } else {
              return false;
            }
          },
          diapValue() {
            if (this.item.filter_type=='Число') {
              let d = (this.minMaxValue.max-this.minMaxValue.min)/6;
              let res = [{id: 0, from: this.minMaxValue.min, to: this.minMaxValue.max, cap:'Все'}];
              let st = this.minMaxValue.min;
              let st1 = st;
              for (let i = 1; i < 7; i++) {
                let end = Math.min(st1+Math.ceil(d*i), this.minMaxValue.max);
                let cap = '' + st + ' - ' + end;
                if (i == 1) {
                  cap = 'Менее ' + end;
                }
                if (i == 6) {
                  cap = '' + st + ' и более';
                  end = this.minMaxValue.max;
                }
                res.push({id: i, from: st, to: end, cap: cap});
                st = end + 1;
              }
              return res;
            } else {
              return {}
            }
          }
        },
        methods: {
          onClick() {
            this.filterOpen = !this.filterOpen;
          },
          onInput(e) {
            this.curDiap = e;
          }
        },
        watch: {
          curDiap(val) {
            if (val == 0) {
              this.minValue = '';
              this.maxValue = '';
            } else {
              this.minValue = this.diapValue[val].from;
              this.maxValue = this.diapValue[val].to;
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
    color: #fc8507;
  }
  .ui-collapse__link_list:hover {
    background-color: #fff7da;
  }
  .ui-collapse__link_list:hover i {
    color: #fc8507;
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
    width: 43%;
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
    color: #fc8507;
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