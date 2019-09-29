<template>
    <div class="page" v-title="'Подтверждение email'">
      <microstone-logo></microstone-logo>
      <reglog-dialog>
        Подтверждение email
      </reglog-dialog>
    </div>
</template>

<script>
  import MicrostoneLogo from '../layout/microstone_logo.vue';
  import ReglogDialog from '../layout/reglog_dialog.vue';
  
  
  import { mapGetters } from 'vuex';
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
                'auth'
          ])
        }
    }
</script>

<style>
</style>