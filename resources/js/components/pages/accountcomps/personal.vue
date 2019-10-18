<template>
    <div class="account-personal">
      <form action="/account/personal" method="post" @submit.prevent="onSubmit">
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
          <div class="account-personal__bithday" :class="{active: bithdayFocus,
            filled: userPersonalLoc.bithday}">
            <date-picker
              lang="ru"
              placeholder=""
              :input-attr="{id: 'Bithday'}"
              @focus="bithdayFocus=true"
              @blur="bithdayFocus=false"
              v-model="userPersonalLoc.bithday"
              format="DD.MM.YYYY"
            >
              <template v-slot:label>
                <label for="Bithday" class="input-row__label">Дата рождения</label>
              </template>
            </date-picker>
          </div>
          <div class="ui-radio ui-radio_list">
            <radio-button v-if="!userPersonalLoc.pol || userPersonalLoc.pol=='n'" :checked="!userPersonalLoc.pol || userPersonalLoc.pol=='n'" :list="true" name="pol" value="n" caption="Не выбран" @input="onInput($event)"></radio-button>
            <radio-button :checked="userPersonalLoc.pol=='m'" :list="true" name="pol" value="m" caption="Мужской" @input="onInput($event)"></radio-button>
            <radio-button :checked="userPersonalLoc.pol=='f'" :list="true" name="pol" value="f" caption="Женский" @input="onInput($event)"></radio-button>
          </div>
          <button :disabled="!enableBtn" class="button-ui button-ui_brand account-personal__submit">Сохранить</button>
          <div class="account-personal__register">
            Дата регистрации: <span>{{userPersonalLoc.date_reg}}</span>
          </div>
      </form>
    </div>
</template>

<script>
  import { mapGetters, mapActions } from 'vuex';
  import inputRow from '../../system/input-row.vue';
  import datePicker from '../../date-picker/index.vue';
  import radioButton from '../../system/radio-button.vue';
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
                bithday: '',
                date_reg: ''
              },
              mount: false,
              validEmail: false,
              validPhone: true,
              confirmCode: '',
              bithdayFocus: false
            }
        }, 
        props: [
        ],
        components: {
          inputRow,
          datePicker,
          radioButton
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
          },
          enableBtn() {
            return (this.userPersonalLoc.phone!=this.userPersonal.phone ||
              this.userPersonalLoc.name!=this.userPersonal.name ||
              this.userPersonalLoc.family!=this.userPersonal.family ||
              this.userPersonalLoc.otchestvo!=this.userPersonal.otchestvo ||
              this.userPersonalLoc.nickname!=this.userPersonal.nickname ||
              this.userPersonalLoc.pol!=this.userPersonal.pol ||
              this.userPersonalLoc.bithday!=this.userPersonal.bithday) && 
              this.validPhone;
          }
        },
        methods: {
          ...mapActions([
              'queryGetToServer',
              'queryPostToServer',
          ]),
          onChange(key,e) {
            this.userPersonalLoc[key] = e;
            if (key=='phone') {
              //this.key = this.key == 1 ? 2 : 1;
            }
            if (key=='email') {
              this.$store.commit('setUserPersonalObj', {sendConfirm: false});
            }
          },
          onInput(e) {
            this.userPersonalLoc.pol = e.value;
          },
          onSubmit() {
            this.queryPostToServer({
              url: '/account/personal',
              params: {
                ...this.userPersonalLoc
              }
            });
          },
          onChangeValid(key, e) {
            if (key=='email') {
              this.validEmail = e;
            } else if (key=='phone') {
              this.validPhone = e;
            }
          },
          setUsPers1() {
            this.$store.commit('setUserPersonalObj', {sendConfirm: true});
          },
          setUsPers2() {
            this.$store.commit('setUserPersonalObj', {sendConfirm: false});
          },
          clickConfirm() {
            this.queryGetToServer({
              url: '/email/resend',
              successAction: this.setUsPers1,
              params: {
                code: 1,
                email: this.userPersonalLoc.email
              }
            });
          },
          clickConfCode() {
            this.queryGetToServer({
              url: '/email/resend',
              successAction: this.setUsPers2,
              params: {
                code: 2,
                cod: this.confirmCode,
                email: this.userPersonalLoc.email
              }
            });
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
    &__bithday {
      &.active, &.filled {
        .input-row__label {
          font-size: 14px;
          top: 5px;
        }
      }
      &.active .mx-input-wrapper {
        box-shadow: 0 9px 28px #d9d9d9;
      }
    }
    .mx-datepicker {
      max-width: 300px;
      margin-bottom: 0;
      width: 100%;
      line-height: 1.42857;
      .mx-input-append {
        z-index: 10;
      }
    }
    .mx-input-wrapper {
      border-radius: 8px;
      display: inline-block;
      position: relative;
      width: 100%;
      height: 54px;
      background: #fff;
      border: 1px solid #e5e5e5;
      overflow: hidden;
      box-sizing: border-box;
      &:hover {
        border-color: #d9d9d9;
        label {
          color: #333;
        }
      }
      input {
        position: absolute;
        top: 0;
        left: 0;
        padding: 18px 0 0 20px;
        height: 100%;
        width: 100%;
        outline: none;
        background: transparent;
        color: #4e4e4e;
        border: none;
        font-weight: bold;
        z-index: 9;
        box-sizing: border-box;
        font-size: 16px;
        font-family: inherit;
        line-height: inherit;
        border-radius: 0;
        box-shadow: none;
      }
    }
    .ui-radio__item {
      padding-left: 3px;
      &:hover {
        background: inherit;
      }
    }
    .ui-radio_list {
      margin-top: 15px;
    }
    &__register {
      border-radius: 8px;
      background-color: #f9f9f9;
      padding: 20px;
      text-align: center;
      margin-top: 20px;
      span {
        font-weight: bold;
      }
    }
    &__submit {
      padding: 20px 20px;
      height: auto;
      margin-top: 20px;
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
      .input-row, .mx-datepicker {
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