<template>
    <div class="wishlist-products__small">
      <div class="small-list">
        <router-link
          :to="'/product/'+el.chpu"
          class="wishlist-product__small" 
          v-for="(el, ind) in smallList"
          :key="el.id+el.characteristic">
          <span class="image">
            <img :src="el.image">
          </span>
          <span :class="{have_charact: el.characteristic}" class="name">{{el.name}}</span>
          <span v-if="el.characteristic" class="characteristic">{{el.charact_name}}</span>
          <span class="price">{{el.price_with_discount}} <i class="fa fa-rub"></i></span>
        </router-link>
        <router-link class="show-all" v-if="countList>5" to="/account/wishlist/0">
          <span>Смотреть все товары</span><i class="fa fa-chevron-right"></i>
        </router-link>
      </div>
      <div class="wishlist-products__buttons">
        <button @click="editList" class="button-ui button-ui_white">Редактировать список</button>
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
          'wishProducts'
        ],
        computed: {
          ...mapGetters([
           'auth'
          ]),
          smallList() {
            return this.wishProducts.slice(0,5);
          },
          countList() {
            return this.wishProducts.length;
          }
        },
        components: {
        },
        methods: {
          editList() {
            this.$router.push('/account/wishlist/0')
          }
        }
    }
</script>

<style lang="less">
  .wishlist-products__small {
      .small-list {
        background: #fff;
        max-height: 175px;
        overflow-y: scroll;
      }
      a {
        color: #333;
        display: block;
        height: 60px;
        padding: 10px;
        text-decoration: none;
        position: relative;
        span {
          display: inline-block;
          vertical-align: middle;
        }
        &+a {
          border-top: 1px solid #ddd;
        }
        .image {
          height: 100%;
          text-align: center;
          img {
            max-height: 40px;
            max-width: 40px;
            vertical-align: middle;
          }
        }
        .name {
          font-size: 13px;
          margin-left: 10px;
          width: calc(100% - 60px - 10px - 110px - 10px);
          &.have_charact {
            position: relative;
            top: -10px;
          }
        }
        .characteristic {
          position: absolute;
          left: 65px;
          bottom: 14px;
          font-size: 12px;
          color: #999;
        }
        .price {
          font-weight: bold;
          margin-left: 10px;
          text-align: right;
          width: 110px;
        }
        &:hover {
          color: #00608d;
          text-decoration: none;
        }
        &.show-all {
          cursor: pointer;
          line-height: 40px;
          text-align: center;
          i {
            color: #777;
            font-size: 12px;
            margin-left: 10px;
            text-align: center;
          }
          &:hover i {
            color: #00608d;
          }
        }
      }
  }
  .wishlist-products__buttons {
    border-top: 1px solid #ddd;
    padding: 20px;
    button {
      width: 200px;
    }
  }

  @media (max-width: 767px){
    .wishlist-products__small {
      .small-list {
        max-height: none;
        overflow-y: hidden;
        > a {
          height: auto;
        }
      }
    }
    .wishlist-products__buttons {
      button {
        width: 100%;
      }
    }
  }
</style>