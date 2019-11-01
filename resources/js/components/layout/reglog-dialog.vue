<template>
    <div class="rl-dialog">
      <slot></slot>
    </div>
</template>

<script>
    export default {
        data() {
            return {
            }
        }
    }
</script>

<style lang="less">
@import '../../../less/vars.less';

  .rl-dialog {
    border: 2px solid #ccc;
    border-radius: 15px;
    max-width: 530px;
    margin: 15px auto;
    background-color: #fff;
    padding-bottom: 15px;
    position: relative;
    min-height: 100px;
  }

  .rl-dialog .error {
    background-color: #fed4d5;
    border-radius: 4px;
    margin-bottom: 10px;
    color: #c20000;
    text-align: center;
    padding: 5px 0px 7px 0px;
  }

  .rl-dialog input {
    font-size: 16px;
    width: 100%;
    height: 30px;
    margin-top: 7px;
    margin-bottom: 10px;
    border: 1px solid #ccc;
    border-radius: 4px;
    padding: 0 3px 0 3px;
  }
  .rl-dialog input[type="checkbox"] {
    visibility: hidden;
    width: 30px;
  }
  .rl-dialog a{
    color: #0493d9;
    text-decoration: none;
    font-weight: 700;
    display: inline;
  }

 .rl-dialog .btn {
    width: 180px;
    font-size: 17px;
    height: 50px;
  }
  .rl-dialog .active-btn {
      border: none;
      color: #fff;
      height: 50px;
      width: 180px;
      font-size: 17px;
      font-weight: bold;
      background-image: linear-gradient(to top, @main-color, @light-color);
      font-weight: bold;
      cursor: pointer;
  }
  .rl-dialog .medium-btn {
    width: 210px;
  }
  .rl-dialog .active-btn:hover {
    background-image: linear-gradient(to bottom, @hover-color, @dark-color);
    box-shadow: inset 0 -2px 0 0 rgba(0,0,0,0.2);
    cursor: pointer;
  }
  .rl-dialog .hr {
    border-top: 2px solid #ccc;
    width: 100%;
    height: 2px;
    margin-top: 5px;
    padding-top: 5px;
  }
  .rl-dialog .valid {
    box-shadow: 0 0 5px #8dfa85!important;
    border-color: #7bdf73!important;
  }
  .rl-dialog .invalid {
    box-shadow: 0 0 5px #ff9e9e!important;
    border-color: #ffa2a2!important;
  }
  .rl-dialog input:focus {
    outline: none;
  }
  .rl-dialog .r-topic {
    font-size: 26px;
    color: #404040;
    margin: 8px 0 15px 40px;
  }
  .rl-dialog .r-footer {
    text-align: center;
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
    .registration {
        position: relative;
        margin-left: 40px;
        margin-right: 40px;
        color: #404040;
    }
    
    .controls {
        margin-top: 10px;
    }
    .captcha {
        margin: 10px 0 5px 0;
    }
    .buttons {
        text-align: center;
    }
</style>