<template>
    <div class="page__mobile-back" v-show="getScreenState<2">
      <i class="fa fa-chevron-left"></i>&nbsp;
      <router-link class="page__mobile-back-link" :to="link">{{name}}</router-link>
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
          'link',
          'name'
        ], 
        computed: {
          ...mapGetters([
            'getScreenState'
          ])
        },
        methods: {
        	
        }
    }
</script>

<style>
  .page__mobile-back {
    display: none;
    padding: 20px 15px;
  }
  .page__mobile-back i {
    font-size: 13px;
  }
  .page__mobile-back-link {
    text-decoration: none;
    color: #333;
    padding-left: 5px;
    margin-right: 5px;
  }
  .page__mobile-back-link:hover {
    color: #00608d;
    text-decoration: underline;
  }
  @media (max-width: 991px) {
    .page__mobile-back {
      display: flex;
      align-items: center;
    }
  }
  @media (max-width: 991px) and (min-width: 768px) {
    .page__mobile-back {
      padding: 20px 0;
    }
  } 
</style>