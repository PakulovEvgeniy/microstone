<template>
    <div v-if="compareEqual" class="compare-info">
      <div class="compare-info__cont">
        <div>
          <div class="compare-info__scroll">
            <div class="compare-info__product" v-for="el in compare.products" :key="el.id">
              <router-link class="compare-info__product-link" :to="'/product/'+el.chpu">
                <div class="compare-info__product-image">
                  <img :src="el.image">
                </div>
                <span class="compare-info__product-title">{{el.name}}</span>
                <i @click.stop.prevent="delFromLocalCompare(el.id)" class="fa fa-times"></i>
              </router-link>
            </div>
          </div>
          <div class="compare-info__buttons">
            <a @click="clearLocalCompare" class="ui-link ui-link_pseudolink"><span class="compare-info__buttons-clear_text">Очистить все</span></a>
            <router-link class="compare-info__buttons-link button-ui button-ui_brand" to="/compare">Сравнить ({{compare.products.length}})</router-link>
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
        ],
        computed: {
          ...mapGetters([
           'compare',
           'compareEqual'
          ]),
        },
        methods: {
          ...mapActions([
            'delFromLocalCompare',
            'clearLocalCompare'
          ])
        },
        mounted() {
          if (!this.compareEqual) {
            this.$store.dispatch('queryForApp', {
              url: '/api/products/compare',
              params: {
                pr: this.compare.items
              }
            });
          }

        }
    }
</script>

<style lang="less">
  .compare-info {
    height: 30px;
    width: 50px;
    position: absolute;
    &__cont {
      position: absolute;
      min-width: 300px;
      max-width: 300px;
      background: #fff;
      box-shadow: 0 8px 12px 0 rgba(0,0,0,0.16);
      border: 1px solid rgba(0,0,0,0.08);
      border-radius: 8px;
      overflow: hidden;
      padding: 0 0 20px 20px;
      top: 5px;
      left: -100px;
    }
    &__scroll {
      overflow-x: auto;
      max-height: 330px;
      padding: 20px 0;
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
        color: #333;
        &:hover {
          text-decoration: none;
          > i {
            visibility: visible;
            color: #333;
          }
        }
        > i {
          visibility: hidden;
          margin-right: 5px;
        }
      }
      &-image {
        display: inline-block;
        margin-right: 8px;
        min-width: 68px;
        text-align: center;
        img {
          width: 68px;
          height: 55px;
        }
      }
      &-title {
        width:  170px;
        margin-right: 5px;
        font-size: 14px;
      }
    }
    &__buttons {
      align-items: center;
      display: flex;
      justify-content: space-between;
      margin-top: 15px;
      padding-right: 20px;
      &-clear_text {
        color: #0094d9;
        &:hover {
          transition: .4s;
          color: #fc5808;
        }
      }
      &-link {
        line-height: 40px;
        padding: 0 10px;
        &:hover {
          text-decoration: none;
          color: #fff;
        }
      }
    }
  }

  @media (max-width: 991px) {
    .compare-info {
      display: none !important;
    }
  }
</style>