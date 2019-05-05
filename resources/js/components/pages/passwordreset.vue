<template>
    <div class="page">
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
  import MicrostoneLogo from '../../components/layout/microstone_logo';
  import ReglogDialog from '../layout/reglog_dialog';
  import ResetEmail from '../forms/resetemail';

  
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
        },
        beforeRouteEnter (to, from, next) {
          next(vm => {
            //if(vm.$store.state.auth) {
            //  return vm.$router.push('/home');
            //}
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