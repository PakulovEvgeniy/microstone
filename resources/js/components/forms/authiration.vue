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
            <input @blur="onBlur('login')" :class="{'valid': validClass('login'), 'invalid' : invalidClass('login')}" id="email" name="email" :value="login.value" @input="onInput($event,'login')" type="email">
            <label for="password">Пароль</label>
            <div class="password-area">
                <input  @blur="onBlur('password')" :type="typePassword" :class="{'valid': validClass('password'), 'invalid' : invalidClass('password')}" id="password" @input="onInput($event, 'password')" :value="password.value" name="password" placeholder="Не менее 8 символов">
                <div class="show-password" title="Показать пароль">
                    <span @click="showPassword = !showPassword">Показать</span>
                    <input type="checkbox" id="cb-show-password" v-model="showPassword">
                    <label for="cb-show-password">
                    </label>
                </div>
            </div>
            
            <div class="controls">
                <a @click="onRemPas">Забыли пароль?</a>
                <div class="buttons">
                    <input type="submit" class="btn medium-btn" :class="{'active-btn': isValid}" :disabled="!isValid" :value="dialog ? 'Продолжить' : 'Войти'">
                </div>
            </div>
            <div v-show="!dialog" class="hr"></div>
        </div>

    </form>
</template>

<script>
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
                isQuery: false,
                isPolicy: false
            }
        },
        props: [
            'dialog'
        ],
        watch: {
          isPolicy(val) {
            this.$emit('changeRegister', val);
          }
        },
        computed: {
            typePassword() {
                return this.showPassword ? 'text' : 'password';
            },
            isValid() {
                return this.login.valid && this.password.valid;
            },
            ...mapGetters([
                'csrf',
                'wishlist',
                'cart',
                'authFrom'
            ])
        },
        methods: {
            onRemPas() {
                if (this.dialog) {
                    this.$emit('forgotPassword');
                } else {
                    this.$router.push('/password/reset');
                }
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
                this.$store.commit('setTempPassword', '');
                if (par && par.isVerify) {
                    this.$emit('continue');
                }
            },
            onSubmit() {
                this.error = '';
                this.queryPostToServer({
                    url: '/login',
                    params: {   
                        _token: this.csrf,
                        email: this.login.value,
                        password: this.password.value,
                        wishlist: this.wishlist.items,
                        cart: this.cart.items,
                        redirect: this.authFrom,
                        dialog: this.dialog
                    },
                    errorAction: () => {
                        this.$store.commit('setTempPassword', '');
                    },
                    successAction: this.sucContinue
                });
            }
        }
    }
</script>

<style>
    
</style>
