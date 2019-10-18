<template>
    <div class="account-changepsw">
      <form action="/account/changepsw" method="post" @submit.prevent="onSubmit">
        <input type="hidden" name="_token" :value="csrf">
        <div class="account-changepsw_input-container">
            <input-row
              label='Старый пароль' 
              inputId='oldPassword' 
              :inputRequired="true" 
              inputType='password'
              :inputValue="oldPassword"
              @change="onChange('oldPassword',$event)"
              @changeValid="onChangeValid('oldPassword', $event)"
              :validate="{'pattern': /.{8}/, 'message': 'Старый пароль должен содержать не менее 8 символов.'}"
            ></input-row>
        </div>
        <div class="account-changepsw_input-container">
            <input-row
              label='Новый пароль' 
              inputId='newPassword' 
              :inputRequired="true" 
              inputType='password'
              :inputValue="newPassword"
              @change="onChange('newPassword',$event)"
              @changeValid="onChangeValid('newPassword', $event)"
              :validate="{'pattern': /.{8}/, 'message': 'Новый пароль должен содержать не менее 8 символов.'}"
            ></input-row>
        </div>
        <button :disabled="!validBtn" type="submit" class="button-ui button-ui_brand">Изменить</button>
      </form>
    </div>
</template>

<script>
    import { mapGetters, mapActions } from 'vuex';
    import inputRow from '../../system/input-row.vue';
    export default {
        data() {
            return {
                oldPassword: '',
                newPassword: '',
                valids: {
                    oldPassword: false,
                    newPassword: false
                }
            }
        }, 
        props: [
        ],
        components: {
            inputRow
        },
        computed: {
            ...mapGetters([
                'csrf'
            ]),
            validBtn() {
              return this.valids.oldPassword && this.valids.newPassword
            }
        },
        methods: {
          ...mapActions([
            'queryPostToServer'
          ]),
          onChange(key, val) {
            this[key] = val;
          },
          onChangeValid(key, val) {
            this.valids[key] = val
          },
          onSubmit() {
            this.queryPostToServer({
              url: '/account/changepsw',
              params: {
                oldPassword: this.oldPassword,
                newPassword: this.newPassword
              }
            });
          }
        }
    }
</script>

<style lang="less">
    .account-changepsw {
      button {
        padding-right: 20px;
        padding-left: 20px; 
      }
    }

    @media (max-width: 991px) {
        .account-changepsw {
          .input-row {
            display: block;
            max-width: 460px;
            width: 100%;
          }
        }
    }
</style>