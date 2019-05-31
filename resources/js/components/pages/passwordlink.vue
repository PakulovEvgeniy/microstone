<template>
    <div class="page" v-title="'Восстановление пароля'">
      <microstone-logo></microstone-logo>
      <reglog-dialog>
        <div class="r-topic">Восстановление пароля</div>
        <sendemail-form></sendemail-form>
        <div class="r-footer">
          <router-link to="/login">Войти в личный кабинет</router-link>
        </div>
      </reglog-dialog>
    </div>
</template>

<script>
  import MicrostoneLogo from '../../components/layout/microstone_logo';
  import ReglogDialog from '../layout/reglog_dialog';
  import sendEmail from '../forms/sendemail';

  
  import { mapGetters } from 'vuex';
    export default {
        data() {
            return {
            }
        },
        components: {
          'microstone-logo': MicrostoneLogo,
          'reglog-dialog': ReglogDialog,
          'sendemail-form': sendEmail
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