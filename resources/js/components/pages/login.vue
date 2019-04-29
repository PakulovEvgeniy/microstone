<template>
    <div class="page">
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
  import MicrostoneLogo from '../../components/layout/microstone_logo';
  import ReglogDialog from '../layout/reglog_dialog';
  import AuthForm from '../forms/authiration';
  
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