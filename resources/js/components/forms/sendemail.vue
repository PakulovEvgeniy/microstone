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
                На адрес электронной почты: <b>{{login.value}}</b>
                отправлено письмо с инструкцией по восстановлению доступа
            </div>
            <div class="hr"></div>
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
                captchaToken: '',
                isQuery: false,
                isSendEmail: false
            }
        },
        computed: {
            isValid() {
                return this.captchaToken && this.login.valid  && !this.isQuery;
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
                } else if (this.login.valid){
                    this.error = '';
                }
            },
            validClass(param) {
                return this[param].edit && this[param].valid
            },
            invalidClass(param) {
                return this[param].edit && !this[param].valid
            },
            ...mapActions({
                showError: 'showError',
                showWait: 'showWait',
                closeWait: 'closeWait'
            }),
            onSubmit() {
                this.error = '';
                this.showWait();
                this.isQuery = true;
                axios.post('/password/email', {   
                    _token: this.csrf,
                    email: this.login.value,
                    captcha: this.captchaToken
                })
                .then(response => {
                    this.isQuery = false;
                    let dat = response.data;
                    if (dat.error) {
                        this.$notify("alert", dat.error, "error");
                        this.resetRecaptcha();
                    }
                    if (dat.success) {
                        this.closeWait();
                        this.isSendEmail = true;
                        this.$notify("alert", dat.status, "success");
                    }
                })
                .catch(e => {
                    this.resetRecaptcha();
                    this.showError(e);
                    this.isQuery = false;

                })
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
