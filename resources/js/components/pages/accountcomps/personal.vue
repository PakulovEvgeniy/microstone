<template>
    <div class="account-personal">
      <form action="/account/personal" method="post">
          <input type="hidden" name="_token" id="csrf-token" :value="csrf">
          <div class="paccount-personal-confirm-container">
            <input-row label='Email' inputId='Email' :inputRequired="true" inputType='email'></input-row>
          </div>
      </form>
    </div>
</template>

<script>
  import { mapGetters, mapActions } from 'vuex';
  import inputRow from '../../system/input-row.vue';
    export default {
        data() {
            return {
            }
        }, 
        props: [
        ],
        components: {
          inputRow
        },
        computed: {
          ...mapGetters([
              'csrf'
          ])
        }
    }
</script>

<style lang="less">
 
</style>