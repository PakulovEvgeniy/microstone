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
        :checked="onlyDifferent"
        @input="onlyDifferent = !onlyDifferent"
      ></ui-toggle>
      <a @click="$emit('clearGroup')" class="clear-compare-list"><i class="fa fa-trash"></i><span>Очистить список</span></a>
      <a @click="editCompare" class="edit-compare-list"><i class="fa fa-edit"></i><span>Редактировать</span></a>
    </div>
    <div v-if="smallScreen" class="compare__products-mobile">
      <div>
        <carousel
          ref="car1"
          v-model="curMobile1" 
          :navigationEnabled="true" 
          :scrollPerPage="false" 
          :paginationEnabled="false"
          :mouseDrag="true"
          :touchDrag="true"
          :loop="true"
          :perPage="1"
          :draggableEnable="false"
          navigationNextLabel="<i class='fa fa-chevron-right'></i>"
          navigationPrevLabel="<i class='fa fa-chevron-left'></i>"
        >
          <slide v-for="el in list1Mobile" :key="el.id">
              <product-compare
                :product="el"
                :lock="false"
                @clickTrash="delFromCompare(el)"
              >
              </product-compare>
          </slide>
        </carousel>
        <div class="slide-controls">
          <span v-show="list1Mobile.length>1">{{curMobile1ItemIndex}} из {{curGroup.items.length}}</span>
        </div>
      </div>
      <div v-if="list2Mobile.length>1">
        <carousel
          ref="car2"
          v-model="curMobile2" 
          :navigationEnabled="true" 
          :scrollPerPage="false" 
          :paginationEnabled="false"
          :mouseDrag="true"
          :touchDrag="true"
          :loop="true"
          :perPage="1"
          :draggableEnable="false"
          navigationNextLabel="<i class='fa fa-chevron-right'></i>"
          navigationPrevLabel="<i class='fa fa-chevron-left'></i>"
        >
          <slide v-for="el in list2Mobile" :key="el.id">
              <product-compare
                :product="el"
                :lock="false"
                @clickTrash="delFromCompare(el)"
              >
              </product-compare>
          </slide>
        </carousel>
        <div class="slide-controls">
          <span>{{curMobile2ItemIndex}} из {{curGroup.items.length}}</span>
        </div>
      </div>
    </div>
    <div v-else class="compare__products">
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
          v-model="curCompareSlide"
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
  import editDialog from './edit-dialog.vue';
  export default {
    data() {
        return {
          reCalc1: false,
          reCalc2: false
        }
    },
    computed: {
      ...mapGetters([
        'compareGroups',
        'getScreenState',
        'smallScreen',
        'itemFixed',
        'itemNotFixed',
        'perPageCustom',
        'baseWidth',
        'list1Mobile',
        'list2Mobile'
      ]),
      curMobile1: {
        get () {
          return this.$store.state.mCompare.curMobile1;
        },
        set (value) {
          this.$store.commit('setCurMobile1', value);
        }
      },
      curMobile2: {
        get () {
          return this.$store.state.mCompare.curMobile2;
        },
        set (value) {
          this.$store.commit('setCurMobile2', value);
        }
      },
      onlyDifferent: {
        get () {
          return this.$store.state.mCompare.onlyDifferent;
        },
        set (value) {
          this.$store.commit('setOnlyDifferent', value);
        }
      },
      curCompareSlide: {
        get () {
          return this.$store.state.mCompare.curCompareSlide;
        },
        set (value) {
          this.$store.commit('setCurCompareSlide', value);
        }
      },
      listKeys() {
        return this.itemNotFixed.map(el => el.id)
      },
      marg() {
        //if (!this.$refs['comp_prod']) {return 0}
        return this.baseWidth*this.itemFixed.length+8;
      },
      curMobile1ItemIndex() {
        if (this.curGroup && this.curGroup.items.length>1) {
          return this.curGroup.items.findIndex((el) => {
            return el.id == this.list1Mobile[this.curMobile1].id;
          }) + 1;
        }
      },
      curMobile2ItemIndex() {
        if (this.curGroup && this.curGroup.items.length>1) {
          return this.curGroup.items.findIndex((el) => {
            return el.id == this.list2Mobile[this.curMobile2].id;
          }) + 1;
        }
      }
    },
    methods: {
      getListExcludeSome(exclude) {
        if (this.curGroup && this.curGroup.items.length) {
          if (this.curGroup.items.length>0) {
            return this.curGroup.items.filter((el, ind) => {
               return ind != exclude;
            });
          } else {
            return [];
          }
        } else {
          return [];
        }
      },
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
      },
      editCompare() {
        this.$store.commit('setBodyBlocked',true);
        this.$modal.show(editDialog, {
        }, {
          height: 'auto'
        });
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
    watch: {
      curMobile1(val) {
        if (!this.$refs.car2) {return;}
        if (!this.reCalc2) {
          this.$refs.car2.$data.dragging = true;
        }
        this.reCalc1 = true;
        let it = this.list1Mobile[val];
        let itOther = this.list2Mobile[this.curMobile2];
        let ind = this.curGroup.items.findIndex((el) => {
          return el.id == it.id;
        });
        this.$store.commit('setExclude2Id', ind);
        let list = this.getListExcludeSome(ind);

        this.curMobile2 = list.findIndex((el) => {
          return el.id == itOther.id;
        });
        this.$nextTick(() => {
          this.$refs.car2.$data.dragging = false;
          this.reCalc1 = false;
        });
      },
      curMobile2(val) {
        if (!this.$refs.car1) {return;}
        if (!this.reCalc1) {
          this.$refs.car1.$data.dragging = true;
        }
        this.reCalc2 = true;
        let it = this.list2Mobile[val];
        let itOther = this.list1Mobile[this.curMobile1];
        let ind = this.curGroup.items.findIndex((el) => {
          return el.id == it.id;
        });
        this.$store.commit('setExclude1Id', ind);
        let list = this.getListExcludeSome(ind);

        this.curMobile1 = list.findIndex((el) => {
          return el.id == itOther.id;
        });
        this.$nextTick(() => {
          this.$refs.car1.$data.dragging = false;
          this.reCalc2 = false;
        });
      }
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
        margin-left: 10px;
      }
    }
    &__products {
      &-mobile {
        display: none;
        margin-top: 30px;
      }
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

  @media (max-width: 767px) {
    .compare {
      &__products {
        display: none;
        &-mobile {
          .slide-controls {
            height: 40px;
            width: 100%;
            text-align: center;
            span {
              line-height: 40px;
            }
          }
          > div {
            width: 50%;
          }
          display: flex;
          .VueCarousel {
            width: 100%;
            .VueCarousel-navigation-prev {
              top: unset;
              bottom: -65px;
              left: 30px;
              outline: none;
              color: #8a8a8a;
              font-size: 24px;
              font-weight: bold;
            }
            .VueCarousel-navigation-next {
              top: unset;
              bottom: -65px;
              right: 30px; 
              outline: none;
              color: #8a8a8a;
              font-size: 24px;
              font-weight: bold;
            }
          }
          .compare__product {
            .icon-lock, .icon-favorite {
              display: none;
            }
            .icon-trash {
              right: 10px;
              top: 0;
            }
          }
        }
      }
    }    
  }

  @media (max-width: 991px) {
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