<template>
    <form class="main-search-form" method="get" :class="{'focus': focus}">
        <div class="main-search-form-container" :class="{'focus': focus}">
        <input @focus="focus = true" @blur="focus = false" type="text" class="main-search-form-input" name="search" placeholder="Поиск по каталогу" autocomplete="off" :value="curValue">
        <span class="main-search-form-button-container">
            <i class="fa fa-search"></i>
        </span>
        </div>
    </form>
</template>

<script>
import { mapGetters, mapActions} from 'vuex';
    export default {
        data() {
            return {
              focus: false  
            }
        },
        props: [
          'curValue'
        ],
        computed: {
           
        },
        methods: {
            
        },
        components: 
        {  }
    }
</script>

<style lang="less">
@import '../../../less/vars.less';
@import '../../../less/smart-grid.less';
    .main-search-form {
    flex-grow: 1;
    padding: 10px 5px;
    z-index: 2;
    transition: all .5s ease-in-out 0s;
    &-container {
      width: 100%;
      position: relative;
      background: #eaeaea;
      border-radius: 8px;
      font-size: 14px;
      border: 1px solid #d9d9d9;
      border-radius: 8px;
      transition: background .3s;
      &:hover, &.focus {
        background-color: #fff;
        box-shadow: 0 4px 12px 0 rgba(0,0,0,0.2);
        input {
          background-color: #fff;
        }
      }
    }
    &-input {
      border-radius: 8px;
      box-shadow: none;
      font-size: 15px;
      background: #eaeaea;
      color: #333;
      border: none;
      outline: none;
      width: 100%;
      padding: 9px 40px 9px 12px;
      transition: background .3s;
    }
    &-button-container {
        color: #8c8c8c;
        position: absolute;
        right: 0;
        top: -2px;
        width: 40px;
        text-align: center;
        cursor: pointer;
        height: 39px;
        i {
          color: #9898a1;
          font-size: 16px !important;
          position: absolute;
          top: 12px;
          left: 8px;
        }
        &:hover i {
           color: #333; 
        }
    }
    .md-block({
      flex-grow: 0;
      width: calc(100% - 215px);
    });
    .sm-block({
      + .mobile-header-btns {
        position: absolute !important;
        right: 0 !important;
      }
      &.focus {
        width: calc(100% - 92px);
      }
    });
  }
</style>
