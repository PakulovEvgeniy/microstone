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
                <div class="policy-area">
                    <div class="policy">
                        <input type="checkbox" id="cb-policy" name="agreeWithPolicy" value="true" checked>
                        <label for="cb-policy" class="policy"></label>
                    </div>
                    <div class="policy-text">
                        Настоящим подтверждаю, что я ознакомлен и согласен с 
                        <router-link to="/police">условиями политики конфиденциальности</router-link>
                    </div>
                </div>
                <div class="buttons">
                    <input type="submit" class="btn medium-btn active-btn" value="Зарегистрироваться">
                </div>
            </div>
            <div class="hr"></div>
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
    .captcha {
        margin: 10px 0 5px 0;
    }
    .policy-area {
        margin: 7px 0 7px 0;
        font-size: 15px;
        position: relative;
        height: 40px;
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
        position: absolute;
    }
    .buttons {
        text-align: center;
    }
</style>
