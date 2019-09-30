<template>
    <div class="page" v-title="'Подтверждение email'">
      <microstone-logo></microstone-logo>
      <reglog-dialog>
        <div class="r-topic">Подтверждение email</div>
        <div class="registration">
          <p>Прежде чем продолжить, пожалуйста проверьте почтовый ящик <b>{{userEmail}}</b> на наличие письма с сылкой для подтверждения. Пожалуйста перейдите по ссылке, указанной в письме, чтобы подтвердить Ваш email.</p><p>Если письмо не получено, попробуйте <a @click="onClick">отправить его повторно</a></p> 
             <p>Либо перейдите на <router-link to="/account/personal">страницу личных данных</router-link> для проверки правильности ввода email и его исправления в случае необходимости.</p>
        </div>
      </reglog-dialog>
    </div>
</template>

<script>
  import MicrostoneLogo from '../layout/microstone_logo.vue';
  import ReglogDialog from '../layout/reglog_dialog.vue';
  
  
  import { mapGetters, mapActions } from 'vuex';
    export default {
        data() {
            return {
            }
        },
        components: {
          'microstone-logo': MicrostoneLogo,
          'reglog-dialog': ReglogDialog
        },
        computed: {
          ...mapGetters([
              'auth',
              'userEmail'
          ])
        },
        methods: {
          ...mapActions({
              showError: 'showError',
              showWait: 'showWait',
              closeWait: 'closeWait'
          }),
          onClick() {
            this.showWait();
            axios.get('/email/resend')
            .then(response => {
                let dat = response.data;
                this.closeWait();
                if (dat.redirect) {
                  this.$router.push(dat.redirect);
                } else {
                  this.$notify("alert", dat.message, "success");
                }
            })
            .catch(e => {
                console.dir(e);
                this.showError(e);
            })
          }
        }
    }
</script>

<style>
</style>