<template>
    <div class="products-page__mobile-buttons">
      <div class="mobile-buttons">
        <div class="mobile-buttons__sort">
          <button @click="$emit('click_sort')" class="button-ui button-ui_white mobile-btn mobile-btn_sort">
            <span class="mobile-btn_sort-text">Сортировка</span>
            <i class="fa fa-sort-amount-asc"></i>
          </button>
        </div>
        <div class="mobile-buttons__filters">
          <button @click="$emit('click_filtr')" class="button-ui button-ui_white mobile-btn mobile-btn_filters">
            <span class="mobile-btn_filters-title">Фильтры
              <span v-if="fltCount>0" class="mobile-btn-counter">{{fltCount}}</span>
            </span>
          </button>
        </div>
        <div class="mobile-buttons__view-mode">
          <button @click="onClick" class="button-ui button-ui_white mobile-btn mobile-btn_view-mode">
            <i class="fa" :class="className"></i>
          </button>
        </div>
      </div>
    </div>
</template>

<script>
  import { mapGetters } from 'vuex';
    export default {
        data() {
            return {
            }
        },
        props: [
          'curMode'
        ],
        computed: {
          ...mapGetters([
            'filterItems'
          ]),
          className() {
            if (this.curMode=='simple') {
              return 'fa-th-list';
            } else {
              return 'fa-th-large';
            }
          },
          fltCount() {
            let res = 0;
            this.filterItems.forEach((el) => {
              if (el.filter_type=='Число') {
                if (el.grp_data.minValue !== '' || el.grp_data.maxValue !== '') res++;
              } else {
                if(el.grp_data.fChecked.length>0) res++;
              }
            });
            return res;
          }
        },
        methods: {
          onClick() {
            this.$emit('changeMode');
          }  
        }
    }
</script>

<style>
  .mobile-buttons {
    display: none;
    flex-wrap: wrap;
    padding: 10px;
  }
  .mobile-buttons__sort {
    flex-grow: 1;
    margin-right: 10px;
    width: 44px;
  }
  .mobile-buttons__filters {
    flex-grow: 1;
    margin-right: 10px;
  }
  .mobile-buttons__view-mode {
    order: 3;
    width: 44px;
  }
  .mobile-btn {
    width: 100%;
    min-width: auto !important;
  }
  .mobile-btn_view-mode {
    min-width: 20px !important;
  }
  .mobile-btn_view-mode i {
    font-size: 24px;
    line-height: 1.7;
  }
  .mobile-btn_sort {
    position: relative;
  }
  .mobile-btn_sort i {
    display: none;
    font-size: 26px;
  }
  .mobile-btn-counter {
    border-radius: 13px;
    background-image: linear-gradient(to bottom, #5BA8E6, #175C95);;
    color: #fff;
    font-size: 14px;
    font-weight: bolder;
    height: 20px;
    padding-top: 1px;
    text-align: center;
    width: 20px;
    margin-left: 5px;
    display: inline-block;
  }
  @media (min-width: 992px) {
    .products-page__mobile-buttons {
      display: none;
    }
  }
  @media (max-width: 991px) {
    .mobile-buttons {
      display: flex;
      min-height: 64px;
    }
  }
  @media (max-width: 767px) {
    .mobile-btn_sort-text {
      display: none; 
    }
    .mobile-buttons__sort {
      flex-grow: 0;
      order: 2;
    }
    .mobile-btn_sort i {
      display: block;
    }
}
</style>