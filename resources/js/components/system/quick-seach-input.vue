<template>
    <div class="ui-input-search ui-input-search_catalog-filter left-top-filters-search-input">
      <input ref="inpFind" class="ui-input-search__input ui-input-search__input_catalog-filter" placeholder="Поиск по категории" :value="valFindCat">
      <span class="ui-input-search__icon ui-input-search__icon_clear ui-input-search__icon_catalog-filter" :class="{'ui-input-search__icon_clear-visible' : visFindCat}" @click="onQuickSeach(false)"><i class="fa fa-times"></i></span>
      <span class="ui-input-search__icon ui-input-search__icon_search ui-input-search__icon_catalog-filter"><i class="fa fa-search" @click="onQuickSeach(true)"></i></span>
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
            'categoryFilters'
          ]),
          visFindCat() {
            return  this.categoryFilters['q'] ? true : false;
          },
          valFindCat() {
            return  this.categoryFilters['q'] || '';
          }
        },
        methods: {
            onQuickSeach(isSearch) {
              if (isSearch) {
                if (!this.$refs['inpFind'].value) {
                  return;
                }
              }
              this.$emit('quickseach', {isSearch: isSearch, value: this.$refs['inpFind'].value}); 
            }
        }
    }
</script>

<style>



.ui-input-search {
    position: relative;
    background: #fff;
    border: 1px solid #d9d9d9;
    border-radius: 8px;
    height: 40px;
    line-height: 38px;
    font-size: 14px;
  }
  .ui-input-search.ui-input-search_catalog-filter {
    border-radius: 8px;
    box-shadow: 0 1px 2px 0 rgba(0,0,0,0.16);
    border: solid 1px transparent;
    background: #fff;
    margin-bottom: 12px;
  }
  .ui-input-search__input {
    color: #333;
    padding: 0 45px 0 12px;
    height: 100%;
    width: 100%;
    border-radius: 8px;
    border: none;
    outline: none;
    font-size: 16px;
  }
  .ui-input-search__input.ui-input-search__input_catalog-filter {
    background-color: #fff;
  }
  .ui-input-search__icon {
    background: linear-gradient(270deg, #fff 45%, rgba(234,234,234,0));
    color: #8c8c8c;
    position: absolute;
    right: 5px;
    top: 0;
    font-size: 16px;
    height: 100%;
    width: 40px;
    text-align: center;
    cursor: pointer;
  }
  .ui-input-search__icon_clear {
    display: none;
  }
  .ui-input-search__icon.ui-input-search__icon_catalog-filter {
    background: linear-gradient(270deg, #fff 45%, rgba(234,234,234,0));
  }
  .ui-input-search__icon_search i:before, .ui-input-search__icon_clear i:before {
    speak: none;
    display: inline-block;
    line-height: 1;
  }
  .ui-input-search__icon:hover {
    color: #333;
  }
  .ui-input-search__icon_clear-visible+.ui-input-search__icon_search {
    display: none;
  }
  .ui-input-search__icon_clear-visible {
    display: block;
  }

  @media (max-width: 991px) {
    .ui-input-search.ui-input-search_catalog-filter {
        height: 48px;
        line-height: 46px;
    }
  }
</style>