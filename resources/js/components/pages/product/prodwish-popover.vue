<template>
    <div class="prodwish-popover">
      <div class="prodwish-popover__cont">
        <div>
          <div class="prodwish-popover__scroll">
            <div class="prodwish-popover__product" v-for="el in list" :key="el.id">
              <a @click.stop="onClick(el)" class="prodwish-popover__product-link"
              :class="{'disable': isInWish({id: product.id, characteristic: el.id})}"
              >
                <div v-if="el.image" class="prodwish-popover__product-image">
                  <img :src="el.image">
                </div>
                <span class="prodwish-popover__product-title">{{el.name}}</span>
              </a>
              <div v-if="isInWish({id: product.id, characteristic: el.id})" class="prodwish-popover__incart">В избранном:<a @click="delFromWish(el)"> удалить</a></div>
            </div>
          </div>
        </div>
      </div>
    </div>
</template>

<script>
  import { mapGetters, mapActions } from 'vuex';
    export default {
        data() {
            return {
            }
        },
        props: [
          'list',
          'elem',
          'product'
        ],
        computed: {
          ...mapGetters([
            'isInWish'
          ]),
        },
        methods: {
          ...mapActions([
          ]),
          onClick(el) {
            if (this.isInWish({id: this.product.id, characteristic: el.id})) {
              return;
            }
            this.$store.dispatch('addToWishList', {id: this.product.id, characteristic: el.id});
            this.$emit('close');
          },
          delFromWish(el) {
            this.$store.dispatch('delFromWishList', {id: this.product.id, characteristic: el.id, group_id: 0});
            this.$emit('close');
          },
          clickHandler(event) {
            const { target } = event;
            const  $el  = this.elem;

            if (!$el.contains(target)) {
              this.$emit('close');
            }
          }
        },
        mounted() {
          document.addEventListener('click', this.clickHandler);
        },
        beforeDestroy() {
            document.removeEventListener('click', this.clickHandler);
        }
    }
</script>

<style lang="less">
  .prodwish-popover {
    height: 30px;
    position: absolute;
    &__cont {
      position: absolute;
      white-space: nowrap;
      background: #fff;
      box-shadow: 0 8px 12px 0 rgba(0,0,0,0.16);
      border: 1px solid rgba(0,0,0,0.08);
      border-radius: 8px;
      overflow: hidden;
      z-index: 1;
      text-align: right;
      font-weight: normal;
    }
    &__incart {
      text-align: right;
      padding: 0 20px;
      color: #999;
      font-size: 13px;
      a {
        margin-left: 10px;
        color: #409FCB;
        border-bottom: 1px dotted #409FCB; 
      }
    }
    &__scroll {
      overflow-x: auto;
      max-height: 330px;
      padding: 10px 0;
    }
    &__product {
      + .compare-info__product {
        margin-top: 10px;
      }
      &-link {
        display: flex;
        align-items: center;
        width: 100%;
        transition-duration: .3s;
        transition-property: color;
        text-decoration: none;
        padding: 5px 20px;
        height: 44px;
        color: #333;
        &:hover {
          text-decoration: none;
          background: #efefef;
          color: #409FCB;
        }
        &.disable {
          color: #999;
          cursor: default;
          &:hover {
            background: #fff;
            color: #999;
          }
        }
      }
      &-image {
        display: inline-block;
        margin-right: 15px;
        min-width: 30px;
        text-align: center;
        img {
          width: 30px;
          height: 30px;
          border-radius: 50px;
          display: block;
        }
      }
      &-title {
        font-size: 14px;
      }
    }
  }
</style>