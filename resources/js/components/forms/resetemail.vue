<template>
    <form novalidate method="post" @submit.prevent="onSubmit">
        <input type="hidden" name="_token" id="csrf-token" :value="this.csrf">
        <div class="registration">
            <div v-if="error" class="error">{{this.error}}</div>
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
                <div class="buttons">
                    <input type="submit" class="btn medium-btn" :class="{'active-btn': isValid}" :disabled="!isValid" value="Изменить пароль">
                </div>
            </div>
            <div class="hr"></div>
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
                return this.password.valid && !this.isQuery;
            },
            ...mapGetters([
                'csrf',
                'resetEmail'
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
                } else if (this.password.valid){
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
                setAuth: 'setAuth',
                showError: 'showError'
            }),
            onSubmit() {
                this.error = '';
                this.isQuery = true;
                axios.post('/password/reset', {   
                    _token: this.csrf,
                    password: this.password.value,
                    password_confirmation: this.password.value,
                    email: this.resetEmail.email,
                    token: this.resetEmail.token
                })
                .then(response => {
                    this.isQuery = false;
                    this.setAuth({
                        dat: response.data,
                        vm: this
                    });
                })
                .catch(e => {
                    this.showError({e: e, vm: this});
                    this.isQuery = false;
                })
            }
        }
    }
</script>

<style>
</style>
