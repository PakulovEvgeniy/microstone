<template>
  <div class="compare__empty">
    <span>Список сравнения пуст</span>
    <span>
      Для добавления товаров к сравнению
      <router-link to="/category">перейдите в каталог</router-link>
    </span>
    <img src="/image/catalog/not-product.png">
  </div>
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
      ])
    },
    methods: {
    },
    components: {
    }
  }
</script>

<style lang="less">
  .compare__empty {
    text-align: center;
    > span {
      display: block;
      &:first-child {
        font-size: 24px;
        margin-bottom: 10px;
      }
    }
    a {
      color: #0094d9;
      text-decoration: none;
      &:hover, &:focus {
        text-decoration: underline;
        color: #00608d;
      }
    }
    img {
      padding-top: 60px;
    }
  }
</style>