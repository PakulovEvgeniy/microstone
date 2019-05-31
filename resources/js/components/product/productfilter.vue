<template>
    <div class="products-filter">
      <form>
        <div class="filter-list">
          <filter-block></filter-block>
          <filter-block></filter-block>
          <filter-block></filter-block>
          <filter-block></filter-block>
        </div>
      </form>
    </div>
</template>

<script>
  import filterBlock from './filterblock.vue';
    export default {
        data() {
            return {
            }
        },
        components: {
          'filter-block': filterBlock
        }
    }
</script>

<style>
  @media (min-width: 992px) {
    .page-products .products-filter {
    width: 220px;
    margin-right: 20px;
    }
    .products-filter .filter-list {
      border: 1px solid #ddd;
    }
    .filter-list {
      border-radius: 4px;
    }
  }

  .products-filter form {
    position: relative;
  }
  .products-filter .filter-list {
    margin-bottom: 0;
    padding: 0;
  }
  .products-filter .filter-list .filter-block {
    position: relative;
  }
  .filter-list {
    background-color: #fff;
    font-size: 13px;
    position: relative;
  }
  .filter-list .filter-block {
    background-color: #fff;
    border-bottom: 1px solid #d8d8d8;
    padding: 20px;
  }
  .filter-list .filter-block:last-child {
    border-bottom: none;
    border-bottom-left-radius: 4px;
    border-bottom-right-radius: 4px;
  }
  .filter-list .filter-block:first-child {
    -moz-border-radius-topleft: 4px;
    -webkit-border-top-left-radius: 4px;
    border-top-left-radius: 4px;
    -moz-border-radius-topright: 4px;
    -webkit-border-top-right-radius: 4px;
    border-top-right-radius: 4px;
  }
  .filter-list .filter-block:hover {
    background: #f7f7f7;
  }
</style>