<template>
  <div class="auth-dialog">
    <reglog-dialog>
      <template v-if="forgotPassword">
        <div class="r-topic">{{!resetEmail ? 'Восстановление пароля' : 'Новый пароль'}}</div>
        <send-email v-if="!resetEmail" :dialog="true" @resetEmail="resetEmail=true"></send-email>
        <reset-email v-if="resetEmail" @continue="onContinue" :dialog="true"></reset-email>
      </template>
      <template v-else>
        <template v-if="auth && !isVerify">
          <div class="r-topic">Подтверждение e-mail</div>
          <confirm-email @continue="onContinue"></confirm-email>
        </template>
        <template v-else>
           <div class="r-topic">{{registerUser ? 'Пожалуйста авторизуйтесь' : 'Укажите свой e-mail'}}</div> 
           <registartion @changeRegister="registerUser=!$event" v-if="!registerUser" :dialog="true" @error="onErrorRegistration($event)"></registartion>
           <authiration v-if="registerUser" :dialog="true" @changeRegister="registerUser=!$event" @continue="onContinue" @forgotPassword="forgotPassword=true"></authiration>
         </template>
       </template>
    </reglog-dialog>
  </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex';
import authiration from './authiration.vue';
import confirmEmail from './confirm-email.vue';
import sendEmail from './sendemail.vue';
import resetEmail from './resetemail.vue';
import registartion from './registartion.vue';
import ReglogDialog from '../layout/reglog-dialog.vue';
export default {
  data() {
      return {
         registerUser: false,
         forgotPassword: false,
         resetEmail: false
      }
    },
    components: {
      authiration,
      ReglogDialog,
      registartion,
      confirmEmail,
      sendEmail,
      resetEmail
    },
    computed: {
        
        ...mapGetters([
          'auth',
          'isVerify'
        ])
    },
    methods: {
      onErrorRegistration(e) {
        if (e && e.response && e.response.data && e.response.data.errors) {
          let err = e.response.data.errors;
          if (err.email && err.email[0] == 'Пользователь с таким e-mail уже зарегистрирован') {
            this.registerUser = true
          }
        }
      },
      onContinue() {
        this.$emit('close');
      }
    },
    watch: {
      '$route' (val) {
        this.$emit('close');
      }
    }
}
</script>

<style lang="less">
    .auth-dialog {
      .rl-dialog {
        margin-top: 0;
        margin-bottom: 0;
        border-radius: 0;
      }
    }
</style>
