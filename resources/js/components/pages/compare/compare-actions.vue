<template>
  <div class="compare__actions">
    <div class="compare__groups"  ref="comp_prod">
      <div class="compare__group" v-for="(el, ind) in compareGroups" :key = "el.id">
        <a @click="$emit('changeGroup', ind)">{{el.name}} ({{el.items.length}})</a>
      </div>
    </div>
    <div class="compare__controls">
      <ui-toggle 
        caption="Только различающиеся параметры"
      ></ui-toggle>
      <a class="clear-compare-list"><i class="fa fa-trash"></i><span>Очистить список</span></a>
    </div>
    <div class="compare__products">
      <div class="fixed" ref="fixed">
        <div 
          v-for="el in itemFixed" 
          :key="el.id" 
          class="fixed-product"
          :style="{'width': baseWidth+'px'}"
        >
          <i @click="setFixed(el,false)" class="fa fa-lock"></i>
          {{el.name}}
        </div>
      </div>
      <div v-if="perPageCustom>0" class="caro" :style="{'margin-left': marg+'px'}">
        <carousel 
          :navigationEnabled="true" 
          ref="carousel" 
          :scrollPerPage="false" 
          :paginationEnabled="false"
          :mouseDrag="false"
          :touchDrag="false"
          :loop="true"
          :perPage="perPageCustom"
          :draggableEnable="true"
          :draggable="true"
        >
          <slide v-for="el in itemNotFixed" :key="el.id">
              <i @click="setFixed(el, true)" class="fa fa-unlock-alt"></i>
              {{el.name}}
          </slide>
        </carousel>
      </div>
    </div>
  </div>
</template>

<script>
  import { mapGetters } from 'vuex';
  import uiToggle from '../../system/ui-toggle.vue';
  import Carousel from '../../Carousel/Carousel.vue';
  import Slide from '../../Carousel/Slide.vue';
  export default {
    data() {
        return {

        }
    },
    computed: {
      ...mapGetters([
        'compareGroups',
        'getScreenState',
        'smallScreen'
      ]),
      itemFixed() {
        return this.curGroup.items.filter(el => el.isFixed)
      },
      itemNotFixed() {
        return this.curGroup.items.filter(el => !el.isFixed)
      },
      perPageCustom() {
        let c = 2;
        if (!this.smallScreen) {
          c = c+=this.getScreenState;
        } else {
          return c;
        }
        return Math.max(c-this.itemFixed.length,0);
      },
      baseWidth() {
        if (this.smallScreen) {
          return 0;
        }
        return 220;
      },
      marg() {
        //if (!this.$refs['comp_prod']) {return 0}
        return this.baseWidth*this.itemFixed.length;
      }
    },
    methods: {
      setFixed(el, val) {
        if (el.isFixed === undefined) {
          this.$set(el, 'isFixed', val);
        } else {
          el.isFixed = val;
        }
        if(typeof(Event) === 'function') {
  // modern browsers
          window.dispatchEvent(new Event('resize'));
        }else{
          // for IE and other old browsers
          // causes deprecation warning on modern browsers
          var evt = window.document.createEvent('UIEvents'); 
          evt.initUIEvent('resize', true, false, window, 0); 
          window.dispatchEvent(evt);
        }
      }
    },
    props: [
      'curGroup'
    ],
    components: {
      uiToggle,
      Carousel,
      Slide
    },
    mounted() {

    }
  }
</script>

<style lang="less">
  .compare {
    &__groups {
      display: flex;
      flex-wrap: wrap;
    }
    &__group {
      margin: 5px 20px 5px 0;
      white-space: nowrap;
      a {
        border-bottom: 1px dotted;
        color: #333;
        font-size: 16px;
        text-decoration: none;
      }
    }
    &__controls {
      background-color: transparent;
      display: flex;
      padding-top: 29px;
      justify-content: space-between;
      .clear-compare-list {
        background-color: transparent;
        font-size: 20px;
        text-decoration: none;
        i {
          color: #afafaf;
          margin-right: 5px;
        }
        span {
          color: #333;
          line-height: 28px;
          vertical-align: top;
          font-size: 13px;
          border-bottom: 1px dotted #000;
        }
      }
    }
    &__products {
      position: relative;
      margin-top: 30px;
      &:after {
        content: '';
        display: block;
        clear: both;
      }
      .VueCarousel {
        width: 100%;
      }
      .caro {
        
      }
      .fixed {
        float: left;
        display: flex;
      }
      .fixed-product {
        min-height: 150px;
      }
      .VueCarousel-slide {
        min-height: 150px;
      }
    }
  }

  @media (max-width: 768px) {
    .compare {
      &__products {
        .fixed {
          display: none;
        }
      }
    }    
  }
</style>