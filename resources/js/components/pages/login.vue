<template>
    <div>my page
      <router-link to="/about">fcgdfgfdgdfg</router-link>
    </div>
</template>

<script>
    export default {
        data() {
            return {
            }
        },
        beforeRouteEnter (to, from, next) {
          next(vm => {
            vm.$store.commit('setNonVisibleMain', true)
          })
        },
        beforeRouteLeave (to, from, next) {
          this.$store.commit('setNonVisibleMain', false);
          next();
        }
    }
</script>

<style>
  
</style>