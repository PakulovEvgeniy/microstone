<template>
  <div class="compare" v-title="title">
    <h1>{{title}}</h1>
    <div v-show="mount" class="compare__block" :class="{empty: !countCompare}">
      <compare-empty v-if="!countCompare"></compare-empty>
      <template v-if="mount && countCompare">
        <compare-actions
          :curGroup="curGroup"
          @changeGroup="$store.commit('setCurGroupIndex', $event)"
          @clearGroup="clearGroup"
        ></compare-actions>
      </template>
    </div>
    <div v-if="mount" class="compare__add" :class="{empty: !countCompare}">
      <button @click="isSearch=!isSearch" class="button-ui button-ui_brand">Добавить товар</button>
      <search-module
        :notCompare="true" 
        v-if="isSearch"
        @close="isSearch=false"
      >
      </search-module>
    </div>
    <compare-params v-if="mount && countCompare"></compare-params>
    <v-dialog/>
  </div>
</template>

<script>
  import { mapGetters } from 'vuex';
  import compareEmpty from './compare/compare-empty.vue';
  import compareActions from './compare/compare-actions.vue';
  import compareParams from './compare/compare-params.vue';
  import searchModule from '../system/search-module.vue';
  export default {
    data() {
        return {
          mount: false,
          isSearch: false
        }
    },
    computed: {
      ...mapGetters([
        'countCompare',
        'compare',
        'compareGroups',
        'curGroup',
        'smallScreen'
      ]),
      title() {
        return 'Сравнение товаров';
      }
    },
    methods: {
      clearGroup() {
        this.$modal.show('dialog', {
          text: 'Вы действительно хотите очистить список сравнения?',
          buttons: [
            {
              title: 'ОК',
              handler: () => { 
                this.$store.dispatch('clearGroup', this.curGroup);
                this.$modal.hide('dialog');
              }
            },
            {
              title: 'Отмена'
            }
          ]
        })
      }
    },
    components: {
      compareEmpty,
      compareActions,
      compareParams,
      searchModule
    },
    beforeRouteEnter (to, from, next) {
      next(vm => {
        vm.$store.commit('setNonVisibleAside', true);
      })
    },
    mounted() {
      this.$store.dispatch('restoreCompare');
      if (this.countCompare>0) {
        this.$store.dispatch('queryGetToServer', {
          url: '/api/products/compare',
          params: {
            pr: this.compare.items
          }
        }).then(() => {
          this.mount = true;  
        });
      } else {
        this.$store.commit('setCompareProducts', []);
        this.mount = true;
      }
    },
    watch: {
      smallScreen(val) {
        if (val) {
          this.$store.commit('setExclude1Id', 1);
          this.$store.commit('setExclude2Id', 0);
          this.$store.commit('setCurMobile1', 0);
          this.$store.commit('setCurMobile2', 0);
        } else {
          this.$store.commit('setCurCompareSlide', 0);
        }
      }
    }
  }
</script>

<style lang="less">
  .compare {
    h1 {
      font-weight: normal;
      font-size: 28px;
      margin: 10px 0;
    }
    &__add {
      background: #fff;
      position: relative;
      > button {
        padding-left: 10px;
        padding-right: 10px;
        margin-left: 20px;
        margin-bottom: 20px;
      }
      &.empty {
        border-bottom-left-radius: 8px;
        border-bottom-right-radius: 8px;
        border: solid 1px transparent;
        box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.16);
      }
    }
    &__block {
      border-top-left-radius: 8px;
      border-top-right-radius: 8px;
      box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.16);
      border: solid 1px transparent;
      background-color: #fff;
      padding: 25px;
    
    }
  }
</style>