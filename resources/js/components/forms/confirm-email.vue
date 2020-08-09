<template>
    <form novalidate method="post" @submit.prevent="onSubmit">
        <input type="hidden" name="_token" id="csrf-token" :value="this.csrf">
        <div class="registration">
            <div>Вам было отправлено письмо с кодом подтверждения. Пожалуйста введите его здесь.</div><br>
            <div v-if="error" class="error">{{this.error}}</div>
            <label for="code">Введите код подтверждения</label>
            <input @blur="onBlur('code')" :class="{'valid': validClass('code'), 'invalid' : invalidClass('code')}" id="code" name="code" :value="code.value" @input="onInput($event,'code')" type="text">
            <div>Не получили письмо? <a @click="onSendAgain">Отправить повторно</a></div>            
            <div class="controls">
                <div class="buttons">
                    <input type="submit" class="btn medium-btn" :class="{'active-btn': isValid}" :disabled="!isValid" value="Продолжить">
                </div>
            </div>
        </div>

    </form>
</template>

<script>
import { mapGetters, mapActions } from 'vuex';
    export default {
        data() {
            return {
                error: '',
                code: {
                    value: '',
                    valid: false,
                    validate: /[0-9]{6}/,
                    edit: false,
                    errTxt: 'Некорректный код подтверждения'
                }
            }
        },
        props: [
        ],
        computed: {
            isValid() {
                return this.code.valid;
            },
            ...mapGetters([
                'csrf',
                'wishlist',
                'cart',
                'authFrom',
                'tempPassword'
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
                } else if (this.code.valid){
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
            onSendAgain() {
              this.queryGetToServer({
                url: '/email/resend',
                params: {
                  code: 3,
                  password: this.tempPassword
                }
              })
            },
            onSubmit() {
                this.error = '';
                this.queryGetToServer({
                    url: '/email/resend',
                    params: {   
                        code: 4,
                        cod: this.code.value
                    },
                    successAction: () => {
                      this.$emit('continue');
                    }
                });
            }
        }
    }
</script>

<style>
    
</style>
