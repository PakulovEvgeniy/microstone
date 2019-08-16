<template>
    <div @click="onClick" class="over-block" :class="{'is-active' : active}">
    </div>
</template>

<script>
 
    export default {
        data() {
            return {
            }
        },
        props: [
          'active'
        ],
        methods: {
          onClick() {
            this.$emit('clickOver');
          }
        },
        watch: {
          active(val) {
            let body = document.querySelector('body');
            if (body) {
              if (val) {
                body.classList.add('blocked');
              } else {
                body.classList.remove('blocked');
              }
            }
          }
        },
        beforeDestroy() {
          let body = document.querySelector('body');
          if (body) {
             body.classList.remove('blocked');
          }
        }
    }
</script>

<style>
  .over-block {
    position: fixed;
    bottom: 0;
    right: 0;
    top: 0;
    left: 0;
    display: none;
    z-index: 8000;
    background: rgba(0,0,0,0.2);
  }
  .over-block.is-active {
    display: block;
  }
</style>