<template>
    <form novalidate method="post" @submit.prevent="onSubmit">
        <input type="hidden" name="_token" id="csrf-token" :value="this.csrf">
        <div class="registration">
          <div v-if="dialog" class="policy-area">
              <div class="policy">
                  <input type="checkbox" id="cb-policy" name="agreeWithPolicy" v-model="isPolicy">
                  <label for="cb-policy" class="policy"></label>
              </div>
              <div class="policy-text">
                  Я не являюсь зарегистрированным пользователем
              </div>
          </div>
            <div v-if="error" class="error">{{this.error}}</div>
            <label for="email">Адрес электронной почты (e-mail)</label>
            <input onfocus="this.removeAttribute('readonly')" readonly @blur="onBlur('login')" :class="{'valid': validClass('login'), 'invalid' : invalidClass('login')}" id="email" name="email" :value="login.value" @input="onInput($event,'login')" type="email">
            <label v-show="!dialog" for="password">Пароль</label>
            <div v-show="!dialog" class="password-area">
                <input onfocus="this.removeAttribute('readonly')" readonly @blur="onBlur('password')" :type="typePassword" :class="{'valid': validClass('password'), 'invalid' : invalidClass('password')}" id="password" @input="onInput($event, 'password')" :value="password.value" name="password" placeholder="Не менее 8 символов">
                <div class="show-password" title="Показать пароль">
                    <span @click="showPassword = !showPassword">Показать</span>
                    <input type="checkbox" id="cb-show-password" v-model="showPassword">
                    <label for="cb-show-password">
                    </label>
                </div>
            </div>
            <div class="controls">
                <div class="captcha">
                    <vue-recaptcha ref="recaptcha" @verify="onVerify" @expired="onExpired" type="checkbox" sitekey="6LcArp8UAAAAAD1CM3AaGQRCQZyN2gFbm0GGkzKk"></vue-recaptcha>
                </div>
                <div v-if="!dialog" class="policy-area">
                    <div class="policy">
                        <input type="checkbox" id="cb-policy" name="agreeWithPolicy" v-model="isPolicy">
                        <label for="cb-policy" class="policy"></label>
                    </div>
                    <div class="policy-text">
                        Настоящим подтверждаю, что я ознакомлен и согласен с 
                        <router-link to="/police">условиями политики конфиденциальности</router-link>
                    </div>
                </div>
                
                <div class="buttons">
                    <input type="submit" class="btn medium-btn" :class="{'active-btn': isValid}" :disabled="!isValid" :value="!dialog ? 'Зарегистрироваться' : 'Продолжить'">
                </div>
            </div>
            <div v-show="!dialog" class="hr"></div>
        </div>

    </form>
</template>

<script>
import VueRecaptcha from 'vue-recaptcha';
import { mapGetters, mapActions } from 'vuex';
    export default {
        data() {
            return {
                error: '',
                showPassword: false,
                login: {
                    value: '',
                    valid: false,
                    validate: /^[-._a-z0-9]+@(?:[a-z0-9][-a-z0-9]+\.)+[a-z]{2,6}$/,
                    edit: false,
                    errTxt: 'Некорректный e-mail'
                },
                password: {
                    value: '',
                    valid: false,
                    validate: /.{8}/,
                    edit: false,
                    errTxt: 'Слишком короткий пароль'
                },
                captchaToken: '',
                isPolicy: true
            }
        },
        props: [
          'dialog'
        ],
        computed: {
            typePassword() {
                return this.showPassword ? 'text' : 'password';
            },
            isValid() {
              if (!this.dialog) {
                return this.isPolicy && this.captchaToken && this.login.valid && this.password.valid;
              }
              return this.captchaToken && this.login.valid;  
            },
            ...mapGetters([
                'csrf',
                'wishlist',
                'cart'
            ])
        },
        watch: {
          isPolicy(val) {
            if (this.dialog) {
              this.$emit('changeRegister', val);
            }
            
          }
        },
        methods: {
            onVerify(response) {
                this.captchaToken = response;
            },
            onExpired() {
                this.captchaToken = '';
            },
            onInput(e, param) {
                this[param].value = e.target.value;
                this[param].valid = this[param].validate.test(this[param].value);
            },
            onBlur(param) {
                this[param].edit = true;
                if (!this[param].valid) {
                   this.error = this[param].errTxt;   
                } else if (this.login.valid && this.password.valid){
                    this.error = '';
                }
            },
            validClass(param) {
                return this[param].edit && this[param].valid
            },
            invalidClass(param) {
                return this[param].edit && !this[param].valid
            },
            ...mapActions([
                'queryPostToServer'
            ]),
            sucContinue(par) {
              if (this.dialog) {
                //this.$emit('continue', par);
              } else {
                this.$store.commit('setTempPassword', '');
              }
            },
            errorContinue(par) {
              if (this.dialog) {
                this.$emit('error', par);
              }
            },
            getRandomPassword(len) {
              if(len > 10) len = 10;
              len = len * (-1);
              return Math.random().toString(36).slice(len);
            },
            onSubmit() {
                this.error = '';
                let params;
                if (this.dialog) {
                  let psd = this.getRandomPassword(8);
                  this.$store.commit('setTempPassword', psd);
                  params = {   
                        _token: this.csrf,
                        email: this.login.value,
                        captcha: this.captchaToken,
                        wishlist: this.wishlist.items,
                        cart: this.cart.items,
                        password: psd,
                        dialog: true
                    }
                } else {
                  params = {   
                        _token: this.csrf,
                        email: this.login.value,
                        password: this.password.value,
                        captcha: this.captchaToken,
                        wishlist: this.wishlist.items,
                        cart: this.cart.items,
                        dialog: false
                    }
                }
                this.queryPostToServer({
                    url: '/register',
                    params,
                    errorAction: this.resetRecaptcha,
                    successAction: this.sucContinue
                });
            },
            resetRecaptcha (par) {
                this.captchaToken = '';
                this.$refs.recaptcha.reset();
                this.$store.commit('setTempPassword', '');
                this.errorContinue(par);
            }
        },
        components: 
        { VueRecaptcha }
    }
</script>

<style>
    
    .policy-area {
        margin: 7px 0 7px 0;
        font-size: 15px;
        position: relative;
    }
    .policy {
        width: 30px;
        position: absolute;
        float: left;
        height: auto;
    }
    .policy input[type=checkbox] {
        margin: 0;
    }
    .policy input[type=checkbox]:checked+label {
        background: -webkit-linear-gradient(top,#59de23 40%,#32ad00 100%);
        background: -moz-linear-gradient(top,#59de23 40%,#32ad00 100%);
        background: -o-linear-gradient(top,#59de23 40%,#32ad00 100%);
        background: -ms-linear-gradient(top,#59de23 40%,#32ad00 100%);
        background: linear-gradient(top,#59de23 40%,#32ad00 100%);
    }
    .policy label {
        cursor: pointer;
        position: absolute;
        width: 18px;
        height: 18px;
        top: 0;
        border-radius: 4px;
        left: 0;
        background: -webkit-linear-gradient(top,#dfe5d7 40%,#b3bead 100%);
        background: -moz-linear-gradient(top,#dfe5d7 40%,#b3bead 100%);
        background: -o-linear-gradient(top,#dfe5d7 40%,#b3bead 100%);
        background: -ms-linear-gradient(top,#dfe5d7 40%,#b3bead 100%);
        background: linear-gradient(top,#dfe5d7 40%,#b3bead 100%);
        filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#dfe5d7',endColorstr='#b3bead',GradientType=0);
    }
    .policy input[type=checkbox]:checked+label:after {
        -ms-filter: "progid:DXImageTransform.Microsoft.Alpha(Opacity=100)";
        filter: alpha(opacity=100);
        opacity: 1;
    }
    .policy label:hover::after {
        -ms-filter: "progid:DXImageTransform.Microsoft.Alpha(Opacity=30)";
        filter: alpha(opacity=30);
        opacity: .5;
    }
    .policy label:after {
        -ms-filter: "progid:DXImageTransform.Microsoft.Alpha(Opacity=0)";
        filter: alpha(opacity=0);
        opacity: 0;
        content: '';
        position: absolute;
        width: 8px;
        height: 5px;
        background: transparent;
        top: 3px;
        left: 4px;
        border: 3px solid #fff;
        border-top: none;
        border-right: none;
        -webkit-transform: rotate(-45deg);
        -moz-transform: rotate(-45deg);
        -o-transform: rotate(-45deg);
        -ms-transform: rotate(-45deg);
        transform: rotate(-45deg);
        box-sizing: content-box;
    }
    .policy-text {
        margin-left: 30px;
    }
</style>
