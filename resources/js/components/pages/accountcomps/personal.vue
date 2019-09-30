<template>
    <div class="account-personal">
      <form action="/account/personal" method="post">
          <input type="hidden" name="_token" id="csrf-token" :value="csrf">
          <div class="account-personal-confirm-container">
            <input-row 
              label='Email' 
              inputId='Email' 
              :inputRequired="true" 
              inputType='email'
              :inputValue="email"
              @change="onChange('email',$event)"
              @changeValid="onChangeValid('email', $event)"
              :validate="{'pattern': /^[-._a-z0-9]+@(?:[a-z0-9][-a-z0-9]+\.)+[a-z]{2,6}$/, 'message': 'Неправильный email адрес.'}"
            ></input-row>
            <router-link to="/email/verify">nhjhgjhjh</router-link>
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
              email: ''
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
        },
        methods: {
          onChange(key,e) {
            this[key] = e;
          },
          onChangeValid(key, e) {
            console.log(e);
          }
        }
    }
</script>

<style lang="less">
 
</style>