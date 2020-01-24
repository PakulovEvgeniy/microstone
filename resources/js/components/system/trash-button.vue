<template>
  <button
    :disabled="disable"
    @click="$emit('click')"
    class="trash button-ui button-ui_white"
  >
    <i class="fa fa-trash"></i>
  </button>
</template>

<script>
import { mapGetters, mapActions } from 'vuex';
export default {
  data() {
    return {
      disable: false
    };
  },
  computed: {
    ...mapGetters([
      'auth'
    ])
  },
  props: [
  ],
  methods: {
    
  }
};
</script>

<style lang="less">
@import '../../../less/vars.less';
  button.trash {
    width: 40px;
  }
</style>