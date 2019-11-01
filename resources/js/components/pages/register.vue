<template>
    <div class="page" v-title="'Зарегистрироваться'">
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
  import MicrostoneLogo from '../layout/microstone-logo.vue';
  import ReglogDialog from '../layout/reglog-dialog.vue';
  import Registration from '../forms/registartion.vue';
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