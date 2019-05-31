<template>
    <div class="page-products">
      <div class="products-page__top-block">
        <product-offers></product-offers>
        <top-filters></top-filters>
      </div>
      <div class="products-page__content">
        
      </div>
    </div>
</template>

<script>
import productOffers from './product-offers.vue';  
import topFilters from './top-filters.vue';
    export default {
        data() {
            return {
            }
        },
        components: {
          'product-offers': productOffers,
          'top-filters': topFilters
        }
    }
</script>

<style>
  .products-page__top-block {
    border-radius: 8px;
    box-shadow: 0 1px 2px 0 rgba(0,0,0,0.16);
    border: solid 1px transparent;
    background: #fff;
    width: 100%;
    margin-bottom: 20px;
  }
</style>