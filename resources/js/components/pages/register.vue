<template>
    <div class="page">
      <microstone-logo></microstone-logo>
      <reglog-dialog>
        <div class="r-topic">Регистрация</div>
        <registration-form></registration-form>
        <div class="r-footer">
          <router-link to="/login">Войти в личный кабинет</router-link>
        </div>
      </reglog-dialog>
    </div>
</template>

<script>
  import MicrostoneLogo from '../../components/layout/microstone_logo';
  import ReglogDialog from '../layout/reglog_dialog';
  import Registration from '../forms/registartion';
  import { mapGetters } from 'vuex';
    export default {
        data() {
            return {
            }
        },
        components: {
          'microstone-logo': MicrostoneLogo,
          'reglog-dialog': ReglogDialog,
          'registration-form': Registration
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