<template>
    <div class="product-info__rating">
      <i class="fa fa-star"></i>
      <i class="fa fa-star"></i>
      <i class="fa fa-star-o"></i>
      <i class="fa fa-star-o"></i>
      <i class="fa fa-star-o"></i>
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
        ],
        computed: {
          ...mapGetters([
          ]),
        },
        methods: {
        }
    }
</script>

<style lang="less">
  
</style>