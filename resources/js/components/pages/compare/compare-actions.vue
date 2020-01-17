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
      <a @click="$emit('clearGroup')" class="clear-compare-list"><i class="fa fa-trash"></i><span>Очистить список</span></a>
      <a class="edit-compare-list"><i class="fa fa-edit"></i><span>Редактировать</span></a>
    </div>
    <div class="compare__products">
      <div class="fixed">
        <div 
          v-for="el in itemFixed" 
          :key="el.id" 
          class="fixed-product"
          :style="{'width': baseWidth+'px'}"
        >
          <product-compare
            :product="el"
            :lock="true"
            @clickFixed="setFixed(el, false)"
          >
          </product-compare>
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
          :listKeys="listKeys"
          @changeInd="changeInd"
        >
          <slide v-for="el in itemNotFixed" :key="el.id">
              <product-compare
                :product="el"
                :lock="false"
                @clickFixed="setFixed(el, true)"
                @clickTrash="delFromCompare(el)"
              >
              </product-compare>
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
  import productCompare from './product-compare.vue';
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
      listKeys() {
        return this.itemNotFixed.map(el => el.id)
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
        return this.baseWidth*this.itemFixed.length+8;
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
      },
      changeInd(e) {
        this.$store.commit('changeDragIndex', {
          offsetIndex: e.offsetIndex,
          id: e.id,
          currentId: this.itemNotFixed[e.currentIndex].id
        });
      },
      delFromCompare(el) {
        this.$store.dispatch('delFromLocalCompare', el.id);
      }
    },
    props: [
      'curGroup'
    ],
    components: {
      uiToggle,
      Carousel,
      Slide,
      productCompare
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
      .clear-compare-list, .edit-compare-list {
        background-color: transparent;
        font-size: 20px;
        text-decoration: none;
        white-space: nowrap;
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
      .edit-compare-list {
        display: none;
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
        box-shadow: 2px 2px 15px 0 rgba(0,0,0,0.15);
        box-sizing: content-box;
      }
      .fixed-product {
        min-height: 150px;
      }
      .VueCarousel-slide {
        min-height: 150px;
      }
      .VueCarousel-navigation {
        &-button {
          color: #8a8a8a;
          &:hover {
            color: #000;
          }
        }
        &-prev {
          left: 15px;
        }
        &-next {
          right: 15px;
        }
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

  @media (max-width: 992px) {
    .compare {
      &__controls {
        .clear-compare-list {
          display: none;
        }
        .edit-compare-list {
          display: block;
        }
      }
    }
  }
</style>