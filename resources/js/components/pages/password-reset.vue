<template>
    <div class="page" v-title="'Восстановление пароля'">
      <microstone-logo></microstone-logo>
      <reglog-dialog>
        <div class="r-topic">Восстановление пароля</div>
        <resetemail-form></resetemail-form>
        <div class="r-footer">
          <router-link to="/login">Войти в личный кабинет</router-link>
        </div>
      </reglog-dialog>
    </div>
</template>

<script>
  import MicrostoneLogo from '../layout/microstone-logo.vue';
  import ReglogDialog from '../layout/reglog-dialog.vue';
  import ResetEmail from '../forms/resetemail.vue';

  
  import { mapGetters } from 'vuex';
    export default {
        data() {
            return {
            }
        },
        components: {
          'microstone-logo': MicrostoneLogo,
          'reglog-dialog': ReglogDialog,
          'resetemail-form': ResetEmail
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