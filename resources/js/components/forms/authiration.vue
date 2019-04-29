<template>
    <form novalidate method="post" @submit.prevent="onSubmit">
        <input type="hidden" name="_token" id="csrf-token" :value="this.csrf">
        <div class="registration">
            <div v-if="error" class="error">{{this.error}}</div>
            <label for="email">Адрес электронной почты (e-mail)</label>
            <input @blur="onBlur('login')" :class="{'valid': validClass('login'), 'invalid' : invalidClass('login')}" id="email" name="email" :value="login.value" @input="onInput($event,'login')" type="email">
            <label for="password">Пароль</label>
            <div class="password-area">
                <input onfocus="this.removeAttribute('readonly')" readonly @blur="onBlur('password')" :type="typePassword" :class="{'valid': validClass('password'), 'invalid' : invalidClass('password')}" id="password" @input="onInput($event, 'password')" :value="password.value" name="password" placeholder="Не менее 8 символов">
                <div class="show-password" title="Показать пароль">
                    <span @click="showPassword = !showPassword">Показать</span>
                    <input type="checkbox" id="cb-show-password" v-model="showPassword">
                    <label for="cb-show-password">
                    </label>
                </div>
            </div>
            <div class="controls">
                <router-link to="/password/reset">Забыли пароль?</router-link>
                <div class="buttons">
                    <input type="submit" class="btn medium-btn" :class="{'active-btn': isValid}" :disabled="!isValid" value="Войти">
                </div>
            </div>
            <div class="hr"></div>
        </div>

    </form>
</template>

<script>
import { mapGetters } from 'vuex';
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
                isQuery: false
            }
        },
        computed: {
            typePassword() {
                return this.showPassword ? 'text' : 'password';
            },
            isValid() {
                return this.login.valid && this.password.valid && !this.isQuery;
            },
            ...mapGetters([
                'csrf'
            ])
        },
        methods: {
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
            onSubmit() {
                this.error = '';
                this.isQuery = true;
                axios.post('/login', {   
                    _token: this.csrf,
                    email: this.login.value,
                    password: this.password.value
                })
                .then(response => {
                    this.isQuery = false;
                    let dat = response.data;
                    if (dat.status && dat.status == 'success') {
                        if (dat.email) {
                            this.$store.commit('setAuth', true);
                            this.$store.commit('setEmail', dat.email);  
                        }
                        if (dat.csrf) {
                            this.$store.commit('setCsrf', dat.csrf);
                            axios.defaults.headers.common['X-CSRF-TOKEN'] = dat.csrf;
                        }
                    }
                    if (dat.redirectTo) {
                        this.$router.push(dat.redirectTo);
                    }
                })
                .catch(e => {
                    this.showError(e);
                    this.isQuery = false;

                })
            },
            showError(e) {
               if (e.response && e.response.data) {
                let err = e.response.data.errors;
                if (err) {
                    for(let el in err) {
                        this.error = err[el][0];
                    }
                }
               } else {
                this.error = e.message;
               }
            }
        }
    }
</script>

<style>
    
</style>
