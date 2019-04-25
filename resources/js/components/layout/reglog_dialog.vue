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

<style>
  .rl-dialog {
    border: 2px solid #ccc;
    border-radius: 15px;
    width: 530px;
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
    padding: 5px 5px 7px 5px;
  }

  .rl-dialog input {
    font-size: 16px;
    width: 444px;
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
  .rl-dialog *{
    box-sizing:content-box;
  }
  .rl-dialog a{
    color: #0493d9;
    text-decoration: none;
    font-weight: 700;
  }

 .rl-dialog .btn {
    width: 180px;
    font-size: 17px;
    height: 50px;
  }
  .rl-dialog .active-btn {
      border: none;
      background-color: #ff6801;
      color: #fff;
      height: 50px;
      width: 180px;
      font-size: 17px;
      font-weight: bold;
      box-shadow: inset 0 1px 0 0 #fff;
      -moz-box-shadow: 0 1px 3px rgba(0,0,0,.5);
      -webkit-box-shadow: 0 1px 3px rgba(0,0,0,.5);
      border-bottom: 1px solid rgba(0,0,0,.25);
      text-shadow: 0 -1px 1px rgba(0,0,0,.25);
      background: -moz-linear-gradient(0% 100% 90deg,#e1630e,#ff6801);
      background: -webkit-gradient(linear,0% 0%,0% 100%,from(#ff6801),to(#e1630e));
      cursor: pointer;
  }
  .rl-dialog .medium-btn {
    width: 210px;
  }
  .rl-dialog .active-btn:hover {
    background: #ff6801;
    background: -moz-linear-gradient(0% 100% 90deg,#e1630e,#e25d02);
    background: -webkit-gradient(linear,0% 0%,0% 100%,from(#e25d02),to(#ff6801));
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
</style>