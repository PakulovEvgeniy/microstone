<template>
    <form novalidate method="post" @submit.prevent="onSubmit">
        <input type="hidden" name="_token" id="csrf-token" :value="this.csrf">
        <div class="registration">
            <div v-if="!isSendEmail">
                <div v-if="error" class="error">{{this.error}}</div>
                <label for="email">Адрес электронной почты (e-mail)</label>
                <input @blur="onBlur('login')" :class="{'valid': validClass('login'), 'invalid' : invalidClass('login')}" id="email" name="email" :value="login.value" @input="onInput($event,'login')" type="email">
                
                <div class="controls">
                    <div class="captcha">
                        <vue-recaptcha ref="recaptcha" @verify="onVerify" @expired="onExpired" type="checkbox" sitekey="6LcArp8UAAAAAD1CM3AaGQRCQZyN2gFbm0GGkzKk"></vue-recaptcha>
                    </div>
                    
                    <div class="buttons">
                        <input type="submit" class="btn medium-btn" :class="{'active-btn': isValid}" :disabled="!isValid" value="Восстановить пароль">
                    </div>
                </div>
            </div>
            <div v-else class="mail-mes">
                <template v-if="dialog">
                    <div>На адрес электронной почты: <b>{{login.value}}</b>
                    отправлено письмо с токеном для восстановления доступа</div>
                    <div v-if="error" class="error">{{this.error}}</div>
                    <label for="code">Скопируйте токен из письма</label>
                    <input @blur="onBlur('code')" :class="{'valid': validClass('code'), 'invalid' : invalidClass('code')}" id="code" name="code" :value="code.value" @input="onInput($event,'code')" type="text">
        
                    <div class="controls">
                        <div class="buttons">
                            <input @click="onClick" type="button" class="btn medium-btn" :class="{'active-btn': isValid}" :disabled="!isValid" value="Продолжить">
                        </div>
                    </div>
                </template>
                <template v-else>
                    На адрес электронной почты: <b>{{login.value}}</b>
                    отправлено письмо с инструкцией по восстановлению доступа
                </template>
            </div>
            <div v-show="!dialog" class="hr"></div>
        </div>
        
    </form>
</template>

<script>
import VueRecaptcha from 'vue-recaptcha';
import { mapGetters, mapActions} from 'vuex';
    export default {
        data() {
            return {
                error: '',
                login: {
                    value: '',
                    valid: false,
                    validate: /^[-._a-z0-9]+@(?:[a-z0-9][-a-z0-9]+\.)+[a-z]{2,6}$/,
                    edit: false,
                    errTxt: 'Некорректный e-mail'
                },
                code: {
                    value: '',
                    valid: false,
                    validate: /^[a-z0-9]+$/,
                    edit: false,
                    errTxt: 'Некорректный токен'
                },
                captchaToken: '',
                isSendEmail: false
            }
        },
        props: [
            'dialog'
        ],
        computed: {
            isValid() {
                if (this.isSendEmail) {
                    return this.code.valid;
                }
                return this.captchaToken && this.login.valid;
            },
            ...mapGetters([
                'csrf'
            ])
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
                } else if ((!this.isSendEmail && this.login.valid) || (this.isSendEmail && this.code.valid)){
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
                'queryPostToServer',
                'queryGetToServer'
            ]),
            onSubmit() {
                this.error = '';
                this.queryPostToServer({
                    url: '/password/email',
                    params: {   
                        _token: this.csrf,
                        email: this.login.value,
                        captcha: this.captchaToken,
                        dialog: this.dialog
                    },
                    errorAction: this.resetRecaptcha,
                    successAction: () => {
                       this.isSendEmail = true; 
                    }
                });
            },
            onClick() {
                this.error = '';
                this.queryGetToServer({
                    url: '/password/reset/'+this.code.value,
                    params: {   
                    },
                    successAction: (par) => {
                       this.$emit('resetEmail'); 
                    }
                });
            },
            resetRecaptcha () {
                this.captchaToken = '';
                this.$refs.recaptcha.reset() // Direct call reset method
            }
        },
        components: 
        { VueRecaptcha }
    }
</script>

<style>
    .mail-mes {
        color: #404040;
        font-size: 15px;
    }
</style>
