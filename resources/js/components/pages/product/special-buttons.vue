<template>
    <div class="special-buttons">
      <div class="compare">
        <a v-tooltip.top="toolNameCompare" @click="addToCompare" :class="{inlist: isInListCompare}"><i class="fa fa-bar-chart" aria-hidden="true"></i><span>{{linkNameCompare}}</span></a>
        <compare-popover v-if="isInListCompare"></compare-popover>
      </div>
      <a :class="{inlist: isInListWish}" v-tooltip.top="toolNameWish" @click="addToWishList"><i class="fa fa-heart-o" aria-hidden="true"></i><span>{{linkNameWish}}</span></a>
    </div>
</template>

<script>
  import { mapGetters } from 'vuex';
  import comparePopover from '../compare/compare-popover.vue';
    export default {
        data() {
            return {
            }
        },
        components: {
          comparePopover
        },
        props: [
          'product',
          'curCharact'
        ],
        computed: {
          ...mapGetters([
            'wishlist',
            'compare'
          ]),
          toolNameCompare() {
            return this.isInListCompare ? 'Удалить из сравнения' : 'Добавить к сравнению';
          },
          isInListCompare() {
            return this.compare.items.indexOf(this.product.id) != -1;
          },
          linkNameWish() {
            return this.isInListWish ? 'В избранном' : 'Избранное';
          },
          toolNameWish() {
            return this.isInListWish ? 'Удалить из избранного' : 'Добавить в избранное';
          },
          linkNameCompare() {
            return this.isInListCompare ? 'В сравнении' : 'Сравнить';
          },
          isInListWish() {
            return !!this.wishlist.items.find((el) => {
              if (this.product.have_charact) {
                return el.id == this.product.id && el.characteristic == this.curCharact.id;
              } else {
                return el.id == this.product.id;
              }
            });
          }
        },
        methods: {
          addToWishList() {
            if (this.isInListWish) {
              this.$store.dispatch('delFromWishList', {id: this.product.id, characteristic: this.product.have_charact ? this.curCharact.id : '', group_id: 0});
            } else {
              this.$store.dispatch('addToWishList', {id: this.product.id, characteristic: this.product.have_charact ? this.curCharact.id : ''});
            }
          },
          addToCompare() {
            if (this.isInListCompare) {
              this.$store.dispatch('delFromLocalCompare', this.product.id);
            } else {
              this.$store.dispatch('addToLocalCompare', this.product.id);
            }
          }
        } 
    }
</script>

<style lang="less">
@import '../../../../less/smart-grid.less';
  .special-buttons {
    margin-top: 10px;
    text-align: left;
    .compare-info {
      display: none;
    }
    .compare {
      display: inline-block;
    }
    .compare:hover .compare-info {
      display: block;
      z-index: 1000;
    }
    > a, .compare > a {
      color: gray;

      margin-right: 15px;
      i {
        margin-right: 5px;
      }
      &.inlist i {
        color: #ff7300;
      }
    }
    > a span, .compare > a span {
      color: #0094d9;
      border-bottom: 1px dotted #0094d9;
    }
    > a.inlist span, .compare > a.inlist span {
      color: #ff7300;
      border-bottom: 1px dotted #ff7300;
    }
  }
</style>