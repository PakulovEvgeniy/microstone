<template>
    <form>
        <div class="registration">
            <div v-if="error" class="error"></div>
            <label for="login">Адрес электронной почты (e-mail)</label>
            <input id="login" class="first" name="login" value="" type="email">
            <label for="password">Пароль</label>
            <div class="password-area">
                <input :type="typePassword" id="password" class="second" name="password" placeholder="Не менее 8 символов">
                <div class="show-password" title="Показать пароль">
                    <span @click="showPassword = !showPassword">Показать</span>
                    <input type="checkbox" id="cb-show-password" v-model="showPassword">
                    <label for="cb-show-password">
                    </label>
                </div>
            </div>
            <div class="controls">
                <div class="captcha">
                    <vue-recaptcha @verify="onVerify" type="checkbox" sitekey="6LcArp8UAAAAAD1CM3AaGQRCQZyN2gFbm0GGkzKk"></vue-recaptcha>
                </div>
            </div>
        </div>

    </form>
</template>

<script>
import VueRecaptcha from 'vue-recaptcha';
    export default {
        data() {
            return {
                error: false,
                showPassword: false
            }
        },
        computed: {
            typePassword() {
                return this.showPassword ? 'text' : 'password';
            }
        },
        methods: {
            onVerify(response) {
                console.log(response);
            }
        },
        components: 
        { VueRecaptcha }
    }
</script>

<style>
    .registration {
        position: relative;
        margin-left: 40px;
        color: #404040;
        width: 450px;
    }
    .password-area {
        font-size: 15px;
        position: relative;
        display: block;
    }
    .show-password {
        width: 88px;
        position: absolute;
        height: 20px;
        top: 13px;
        right: 5px;
        text-align: right;
        float: right;
        cursor: pointer;
    }
    .show-password span {
        top: 0;
        position: relative;
        font-size: 15px;
    }
    .show-password label {
        position: absolute;
        width: 17px;
        height: 17px;
        top: 0;
        border-radius: 3px;
        left: 0;
        border: 1px solid #898888;
    }

    .show-password label::after {
        -ms-filter: "progid:DXImageTransform.Microsoft.Alpha(Opacity=0)";
        filter: alpha(opacity=0);
        opacity: 0;
        content: '';
        position: absolute;
        width: 8px;
        height: 5px;
        background: transparent;
        top: 3px;
        left: 3px;
        border: 3px solid #007aff;
        border-top: none;
        border-right: none;
        -webkit-transform: rotate(-45deg);
        -moz-transform: rotate(-45deg);
        -o-transform: rotate(-45deg);
        -ms-transform: rotate(-45deg);
        transform: rotate(-45deg);
        box-sizing: content-box;
    }
    .show-password label:hover::after {
        opacity: 0.3;
    }
    .show-password input[type="checkbox"]:checked + label::after {
        opacity: 1;
    }
    .controls {
        margin-top: 10px;
    }
</style>
