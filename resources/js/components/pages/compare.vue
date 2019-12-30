<template>
  <div class="compare" v-title="title">
    <h1>{{title}}</h1>
    <div v-show="mount" class="compare__block">
      <compare-empty v-if="!countCompare"></compare-empty>
      <template v-if="mount && countCompare">
        <compare-actions
          :curGroup="curGroup"
          @changeGroup="curGroupIndex=$event"
        ></compare-actions>
      </template>
    </div>
  </div>
</template>

<script>
  import { mapGetters } from 'vuex';
  import compareEmpty from './compare/compare-empty.vue';
  import compareActions from './compare/compare-actions.vue';
  export default {
    data() {
        return {
          mount: false,
          curGroupIndex: 0
        }
    },
    computed: {
      ...mapGetters([
        'countCompare',
        'compare',
        'compareGroups'
      ]),
      title() {
        return 'Сравнение товаров';
      },
      curGroup() {
        if (!this.compareGroups.length) {
          return [];
        }
        return this.compareGroups[this.curGroupIndex];
      }
    },
    methods: {
    },
    components: {
      compareEmpty,
      compareActions
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
    &__block {
      border-radius: 8px;
      box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.16);
      border: solid 1px transparent;
      background-color: #fff;
      padding: 25px;
    }
  }
</style>