<template>
    <form class="main-search-form" method="get" :class="{'focus': focus}">
        <div class="main-search-form-container">
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
      height: 40px;
      font-size: 14px;
      border: 1px solid #d9d9d9;
      border-radius: 8px;
    }
    &-input {
      border-radius: 8px;
      box-shadow: none;
      font-size: 15px;
      background: #eaeaea;
      color: #333;
      padding-left: 12px;
      height: 95%;
      width: calc(100% - 45px);
      border: none;
      outline: none;
    }
    &-button {
      border-radius: 0 4px 4px 0;
      box-shadow: none;
      position: relative;
      height: 40px;
      width: 60px;
      border: 1px solid #fff;
      background: #fff;
      outline: none;
      border-left: none;
      cursor: pointer;
      &-container {
        color: #8c8c8c;
        position: absolute;
        right: 8px;
        top: -2px;
        width: 40px;
        text-align: center;
        cursor: pointer;
        height: 39px;
        i {
          color: #9898a1;
          font-size: 16px !important;
          position: absolute;
          top: 13px;
        }
        &:hover i {
           color: #333; 
        }
      }
    }
  }

  @media (min-width: 992px) {
      .main-search-form-container, .main-search-form-container input {
          background: #fff;
      }
  }

  @media (max-width: 991px) {
      .main-search-form {
        flex-grow: 0;
        width: calc(100% - 197px);
      }
  }



  @media (max-width: 767px) {
    .main-search-form + .mobile-header-btns {
      position: absolute;
      right: 0;
    }
    .main-search-form.focus {
      width: calc(100% - 92px);
    }
  }
</style>
