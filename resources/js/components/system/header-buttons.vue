<template>
  <router-link 
    class="header-button" 
    :to="link">
      <i class="fa" :class="icon"></i>
      <span>{{name}}</span>
      <span v-show="qty" class="btn-badge">{{qty}}</span>
  </router-link>
</template>

<script>

  import { mapGetters } from 'vuex';
    export default {
        data() {
            return {
  
            }
        },
        props: [
          'link',
          'name',
          'icon',
          'qty'
        ],
        computed: {
        },
        components: {
        },
        methods: {
          
        }
    }
</script>

<style lang="less">
@import '../../../less/vars';
  .header-button {
    color: #333;
    font-size: 15px;
    position: relative;
    transition-duration: .3s;
    transition-property: color;
    i {
      color: #d9d9d9;
      font-size: 20px;
      margin: 0 8px 0 0;
    }
    .btn-badge {
      left: 8px;
      top: 5px;
      line-height: normal;
    }
  }
</style>