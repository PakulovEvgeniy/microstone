<template>
    <div class="qty-spinner">
      <button @click="change(-1)" class="qty-spinner__dec">-</button>
      <input @input="input" type="text" autocomplete="off" class="qty-spinner__input" placeholder="1" :value="value">
      <button @click="change(1)" class="qty-spinner__inc">+</button>
    </div>
</template>

<script>
    export default {
        data() {
            return {
              time: undefined
            }
        },
        props: [
          'value'
        ],
        computed: {
          
        },
        methods: {
          change(val) {
            let res = this.value + val;
            if (res<=0) {res = 1;}
            this.$emit('input', res);
          },
          input(e) {
            let res = parseInt(e.target.value);
            if (!res || res<=0) {
              res = 1;
            }
            if (this.time) {clearTimeout(this.time);}
            this.time = setTimeout(() => {
              e.target.value = res;
              this.$emit('input', res);
            }, 500);
          }
        } 
    }
</script>

<style lang="less">
  .qty-spinner {
    font-size: 0;
    &__dec, &__inc {
      margin: 0;
      font-weight: bold;
      padding: 3px;
      width: 1.2em;
      background-color: #f5f5f5;
      border: solid 1px #d2d2d2;
      background-image: linear-gradient(to bottom,#f5f5f5,#d7d7d7);
      color: #444;
      cursor: pointer;
      font-size: 18px;
      outline: none;
      &:hover {
        background-color: #fff;
        background-image: linear-gradient(to bottom,#fff 0,#d7d7d7 37%,#d7d7d7 63%,#fff 100%);
      }
    }
    &__dec {
      border-right: 0 none;
      border-radius: 3px 0 0 3px;
    }
    &__inc {
      border-left: 0 none;
      border-radius: 0 3px 3px 0;
    }
    &__input {
      border: 1px solid #ccc;
      font-size: 18px;
      margin: 0;
      text-align: center;
      border-radius: 0;
      outline: none;
      font-weight: bold;
      padding: 3px;
      width: 3.2em;
    }
  }
</style>