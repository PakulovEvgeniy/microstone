<template>
    <div class="page__mobile-back">
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
        ]
    }
</script>

<style lang="less">
@import '../../../less/smart-grid.less';
  .page__mobile-back {
    display: none;
    padding: 20px 15px 5px 15px;
    i {
      font-size: 13px;
    }
    &-link {
      text-decoration: none;
      color: #333;
      padding-left: 5px;
      margin-right: 5px;
      &:hover {
        color: #00608d;
        text-decoration: underline;
      }
    }
    .md-block({
      display: flex;
      align-items: center;
    });
    .from-to(@break_sm + 1px, @break_md, {
      padding: 20px 0 5px 0;
    });
  }
</style>