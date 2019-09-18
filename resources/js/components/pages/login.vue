<template>
    <div class="page" v-title="'Войти в личный кабинет'">
      <microstone-logo></microstone-logo>
      <reglog-dialog>
        <div class="r-topic">Войти в личный кабинет</div>
        <auth-form></auth-form>
        <div class="r-footer">
          <router-link to="/register">Регистрация</router-link>
        </div>
      </reglog-dialog>
    </div>
</template>

<script>
  import MicrostoneLogo from '../layout/microstone_logo.vue';
  import ReglogDialog from '../layout/reglog_dialog.vue';
  import AuthForm from '../forms/authiration.vue';
  
  import { mapGetters } from 'vuex';
    export default {
        data() {
            return {
            }
        },
        components: {
          'microstone-logo': MicrostoneLogo,
          'reglog-dialog': ReglogDialog,
          'auth-form': AuthForm
        },
        computed: {
          ...mapGetters([
                'auth'
          ])
        }
    }
</script>

<style>
</style>