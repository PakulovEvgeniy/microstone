<template>
  <section class="compare__params">
    <div class="compare__params-panelview">
      <div class="compare__params-table_title">
        Параметры
      </div>
      <div 
        class="compare__params-table_row" 
        v-for="el in tableOfParams" 
        :key="el.param_type_id"
        :class="{'different': !el.notDifferent}"
        v-show="!el.notDifferent || (el.notDifferent && !onlyDifferent)"
        >
        <div class="compare__params-table_title_col">
          <span v-if="getScreenState>1">{{el.name}}</span>
          <a v-else class="mobile-popover" v-tooltip.bottom="{ content: 'lorem erjtkl erjtkwjre tjeklrt wekrjwkl rwkjerkl wekjrlkwejrlkwejrl wekrjlkwejr kwejr klwejrlkjweklrjwekljrlkwejrl kwejrkljweklrj wklejr lkwejr klwejrlkwejrlkwejrlkjwelkrjlwk ejr kwejrlkwej', classes: ['lite'], trigger: 'click'}">
            {{el.name}}
            <i class="fa fa-question-circle-o"></i>
          </a>
            <a v-if="getScreenState>1" class="show-popover" v-tooltip.right="{ content: 'lorem erjtkl erjtkwjre tjeklrt wekrjwkl rwkjerkl wekjrlkwejrlkwejrl wekrjlkwejr kwejr klwejrlkjweklrjwekljrlkwejrl kwejrkljweklrj wklejr lkwejr klwejrlkwejrlkwejrlkjwelkrjlwk ejr kwejrlkwej', classes: ['lite']}">
              <i class="fa fa-question-circle-o"></i>
            </a>
        </div>
        <div class="compare__params-val_cont">
          <div 
            :class="{'fixed' : ind < fixCount}" 
            class="compare__params-table_title_val" 
            v-for="(it, ind) in el.params" 
            :key="ind"
            :style="[ind < fixCount ? {width: baseWidth+'px'} : {}]" 
          ><p>{{it}}</p></div>
        </div>
      </div>
    </div>
  </section>
</template>

<script>
  import { mapGetters } from 'vuex';
  export default {
    data() {
        return {
        }
    },
    computed: {
      ...mapGetters([
        'tableOfParams',
        'itemFixed',
        'smallScreen',
        'baseWidth',
        'getScreenState'
      ]),
      fixCount() {
        if (this.smallScreen || !this.itemFixed || this.itemFixed.length == 0) { return 0; }
        return this.itemFixed.length;
      },
      onlyDifferent() {
        return this.$store.state.mCompare.onlyDifferent;
      } 
    },
    methods: {
    },
    components: {
    }
  }
</script>

<style lang="less">
  .tooltip.lite {
    box-shadow: inset 0 -1px 0 rgba(0,0,0,0.1);
    background: #f7f7f7;
    border: 1px solid #e3e3e3;
    border-radius: 5px;
    .tooltip-inner {
      font-size: 16px;
      line-height: 1.25;
      padding: 9px 14px;
      border: unset;
      background: unset;
      color: #333;
      max-width: 400px;
      overflow: hidden;
    }
    .tooltip-arrow {
      border-color: #f7f7f7;
    }
  }

  .compare {
    &__params {
      &-panelview {
        overflow: hidden;
        background: #fff;
        border-bottom-left-radius: 8px;
        border-bottom-right-radius: 8px;
      }
      &-table_row {
        padding: 5px 25px;
        border-bottom: 1px solid #d8d8d8;
        &.different {
          background-color: #daf3fe;
        }
        &:hover {
          a.show-popover {
            display: inline-block;
          }
        }
      }
      &-val_cont {
         display: flex;
      }
      &-table_title {
        border-bottom: 1px solid #d8d8d8;
        background: #fff;
        font-size: 18px;
        font-weight: bold;
        width: 100%;
        &_col {
          font-size: 16px;
          line-height: 29px;
          padding: 5px 10px 5px 0;
          vertical-align: middle;
          color: gray;
          a.show-popover {
            display: none;
          }
          a.mobile-popover {
            color: #808080;
            font-weight: bold;
            font-size: 13px;
            text-decoration: none;
          }
          a.show-popover i {
            color: #ddd;
            font-size: 22px;
          }
          a.show-popover:hover i {
            color: #555;
          }
        }
        &_val {
          width: 5px;
          font-size: 16px;
          line-height: 26px;
          padding: 5px 10px 5px 20px;
          vertical-align: middle;
          flex-grow: 1;
          &.fixed {
            flex-grow: 0;
          }
          p {
            margin: 0;
          }
        }
      }
    }
  }

 @media (min-width: 992px) {
  .compare {
    &__params {
      
      &-table_title {
        padding: 2em 20px;
      }
    }
  }
 }

 @media (max-width: 767px) {
  .compare {
    &__params {
      
      &-table_title {
        padding: .8em 2em .8em .8em;
        border-top: 1px solid #d8d8d8;
      }
    }
  }
   .tooltip.lite {
      left: -5px;
      right: 5px;
      .tooltip-arrow {
        display: none;
      }
   }
 }

 @media (max-width: 991px) {
  .compare {
    &__params {
      
      &-table_title {
        padding: 2em 20px 1em;
        border-top: 1px solid #d8d8d8;
      }
    }
  }
 }

</style>