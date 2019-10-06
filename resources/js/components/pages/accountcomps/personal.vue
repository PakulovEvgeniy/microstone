<template>
    <div class="account-personal">
      <form action="/account/personal" method="post">
          <input type="hidden" name="_token" id="csrf-token" :value="csrf">
          <div class="account-personal__confirm-container verify">
            <div class="account-personal__verify" v-if="isVer">Ваш e-mail подтвержден</div>
            <div class="account-personal__notverify" v-if="isNotVer">Ваш e-mail еще не подтвержден</div>
            <input-row 
              label='Email' 
              inputId='Email' 
              :inputRequired="true" 
              inputType='email'
              :inputValue="userPersonalLoc.email"
              @change="onChange('email',$event)"
              @changeValid="onChangeValid('email', $event)"
              :validate="{'pattern': /^[-._a-z0-9]+@(?:[a-z0-9][-a-z0-9]+\.)+[a-z]{2,6}$/, 'message': 'Неправильный email адрес.'}"
            ></input-row>
            <div class="account-personal__btn-container">
              <button @click="clickConfirm" :class="{'show': mount && (isChangeEmail || !isVerify) && validEmail && !userPersonal.sendConfirm}" class="button-ui button-ui_brand" type="button">Подтвердить</button>
              <button @click="clickCancel" :class="{'show': mount && (isChangeEmail || userPersonal.sendConfirm)}" class="button-ui button-ui_white" type="button">Отменить</button>
            </div>
            <div v-show="userPersonal.sendConfirm" class="confirm-email-result">
              <div class="confirm-email-title">
                Мы отправили письмо на указанный адрес.
              </div>
              <div class="confirm-email-body">
                <div>Введите код подтверждения указанный в письме</div>
                <input v-model="confirmCode" type="text" name="confirm" placeholder="Код подтверждения">
                <button @click="clickConfCode" type="button" class="button-ui button-ui_brand">Подтвердить</button>
              </div>
            </div>            
          </div>
          <div class="account-personal__confirm-container">
            <input-row
               
              label='Телефон' 
              inputId='Phone' 
              :inputRequired="false" 
              :inputMask="/[0-9]/"
              :maxInputLength="10"
              maskText="+7 ### ###-##-##"
              maskHolder="+7 000 000-00-00"
              inputType='tel'
              :inputValue="userPersonalLoc.phone"
              @change="onChange('phone',$event)"
              @changeValid="onChangeValid('phone', $event)"
              :validate="{'pattern': /^[0-9]{10,10}$/, 'message': 'Неправильный номер телефона.'}"
            ></input-row>
          </div>
          <input-row
              label='Фамилия' 
              inputId='Family' 
              :inputRequired="false" 
              inputType='text'
              :inputValue="userPersonalLoc.family"
              @change="onChange('family',$event)"
              @changeValid="onChangeValid('family', $event)"
          ></input-row>
          <input-row
              label='Имя' 
              inputId='Name' 
              :inputRequired="false" 
              inputType='text'
              :inputValue="userPersonalLoc.name"
              @change="onChange('name',$event)"
              @changeValid="onChangeValid('name', $event)"
          ></input-row>
          <input-row
              label='Отчество' 
              inputId='Otchestvo' 
              :inputRequired="false" 
              inputType='text'
              :inputValue="userPersonalLoc.otchestvo"
              @change="onChange('otchestvo',$event)"
              @changeValid="onChangeValid('otchestvo', $event)"
          ></input-row>
          <input-row
              label='Никнейм' 
              inputId='Nickname' 
              :inputRequired="false" 
              inputType='text'
              :inputValue="userPersonalLoc.nickname"
              @change="onChange('nickname',$event)"
              @changeValid="onChangeValid('nickname', $event)"
          ></input-row>
      </form>
    </div>
</template>

<script>
  import { mapGetters, mapActions } from 'vuex';
  import inputRow from '../../system/input-row.vue';
  //import datepicker from 'vuejs-datepicker';
    export default {
        data() {
            return {
              userPersonalLoc: {
                email: '',
                phone: '',
                name: '',
                family: '',
                otchestvo: '',
                nickname: '',
                pol: '',
                bithday: ''
              },
              mount: false,
              validEmail: false,
              confirmCode: '',
              key: 1
            }
        }, 
        props: [
        ],
        components: {
          inputRow,
          //datepicker
        },
        computed: {
          ...mapGetters([
              'csrf',
              'userPersonal',
              'isVerify'
          ]),
          isChangeEmail() {
            return this.userPersonalLoc.email != this.userPersonal.email;
          },
          isVer() {
            return this.isVerify && !this.isChangeEmail;
          },
          isNotVer() {
            return !this.isVerify && !this.isChangeEmail;
          }
        },
        methods: {
          ...mapActions({
              showError: 'showError',
              showWait: 'showWait',
              closeWait: 'closeWait'
          }),
          onChange(key,e) {
            this.userPersonalLoc[key] = e;
            if (key=='phone') {
              //this.key = this.key == 1 ? 2 : 1;
            }
            if (key=='email') {
              this.$store.commit('setUserPersonalObj', {sendConfirm: false});
            }
          },
          onChangeValid(key, e) {
            if (key=='email') {
              this.validEmail = e;
            }
          },
          clickConfirm() {
            this.showWait();
            axios.get('/email/resend', {params: {code: 1, email: this.userPersonalLoc.email}})
            .then(response => {
                let dat = response.data;
                this.closeWait();
                if (dat.redirect) {
                  this.$router.push(dat.redirect);
                } else {
                  if (dat.status == 'OK') {
                    this.$store.commit('setUserPersonalObj', {sendConfirm: true});
                    this.$notify("alert", dat.message, "success");
                  }
                }
            })
            .catch(e => {
              this.showError(e);
            })
          },
          clickConfCode() {
            this.showWait();
            axios.get('/email/resend', {params: {code: 2, cod: this.confirmCode, email: this.userPersonalLoc.email}})
            .then(response => {
                let dat = response.data;
                this.closeWait();
                if (dat.redirect) {
                  this.$router.push(dat.redirect);
                } else {
                  if (dat.status == 'OK') {
                    this.$store.commit('setUserPersonalObj', {sendConfirm: false, email: dat.email});
                    this.$store.commit('setEmail', dat.email);
                    this.$store.commit('setVerify', true);
                    this.$notify("alert", dat.message, "success");
                  }
                }
            })
            .catch(e => {
              this.showError(e);
            })
          },
          clickCancel() {
            this.userPersonalLoc.email=this.userPersonal.email;
            this.$store.commit('setUserPersonalObj', {sendConfirm: false});
          }
        },
        mounted() {
          for(let key in this.userPersonal) {
            this.userPersonalLoc[key] = this.userPersonal[key];
          }
          this.mount = true;
          //this.$store.commit('setUserPersonalObj', {sendConfirm: false});
        }
    }
</script>

<style lang="less">
  .account-personal {
    &__confirm-container {
      position: relative;
      &.verify {
        padding-top: 25px;
      }
      .input-row {
        display: inline-block;
      }
    }
    &__verify {
      color: #6ba833;
      padding-left: 10px;
      padding-bottom: 5px;
      font-size: 14px;
      font-weight: bold;
      position: absolute;
      top: 0;
    }
    &__notverify {
      color: red;
      padding-left: 10px;
      padding-bottom: 5px;
      font-size: 14px;
      font-weight: bold;
      position: absolute;
      top: 0;
    }
    &__btn-container {
      display: inline-block;
      vertical-align: top;
      margin-left: 24px;
      button {
        padding-left: 15px;
        padding-right: 15px;
        height: 54px;
        width: 130px;
        display: none;
        &.show {
          display: inline-block;
        }
      }
    }
  }
  .confirm-email {
    &-result {
      background: #ebf7fd;
      display: block;
      border-radius: 8px;
      max-width: 460px;
      width: 100%;
      position: relative;
      padding: 10px 15px;
    }
    &-title {
      margin-bottom: 10px;
      font-weight: bold;
    }
    &-body {
      div {
        margin-bottom: 10px;
      }
      input {
        border: none;
        outline: none;
        border-radius: 4px;
        padding: 11px 5px;
        font-size: 17px;
      }
      button {
        padding-left: 5px;
        padding-right: 5px;
      }
    }
  }

   @media (max-width: 991px) {
    .account-personal {
      .input-row {
        display: block;
        max-width: 460px;
        width: 100%;
      }
      &__btn-container {
        display: block;
        margin-left: 0;
        margin-bottom: 13px;
      }
    }
  }
</style>