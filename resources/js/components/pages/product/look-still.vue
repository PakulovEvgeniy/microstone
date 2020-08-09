<template>
    <div class="look-still">
      <h3>Посмотреть еще</h3>
      <ul>
        <li v-if="parBrand">
          <span>Другие товары этого производителя: </span>
          <router-link :to="parBrand.filtr_chpu">{{product.cat_name}} {{parBrand.value}}</router-link>
        </li>
        <li v-if="parBrand">
          <span>Вся продукция производителя</span>
          <router-link :to="'/manufacturer/'+chpuBrand">{{parBrand.value}}</router-link>
        </li>
        <li>
          <span>Посмотреть все</span>
          <router-link :to="'/category/' + product.cat_chpu">{{product.cat_name}}</router-link>
        </li>
        <li>
          <router-link to="/category">Посмотреть весь каталог</router-link>
        </li>
      </ul>
    </div>
</template>

<script>
    export default {
        data() {
            return {
            }
        },
        props: [
          'prodParams',
          'product'
        ],
        computed: {
          parBrand() {
            return this.prodParams.find((el) => {
              return el.name == 'Производитель';
            });
          },
          chpuBrand() {
            if (!this.parBrand) {return '';}
            let br = this.product.manuf.find((el) => {
              return el.name == this.parBrand.value;
            });
            if (br) {return br.chpu;}
            return '';
          }
        },
        methods: {
          
        } 
    }
</script>

<style lang="less">
@import '../../../../less/vars.less';
  .look-still {
    padding: 20px;
    h3 {
      font-weight: bold;
      margin-bottom: 20px;
    }
    ul {
      padding-left: 20px;
    }
    li {
      list-style-type: square;
      padding: 3px 0;
    }
    a {
      color: @main-color;
      border-bottom: 1px solid;
      &:hover {
        color: tomato;
      }
    }
  }
</style>