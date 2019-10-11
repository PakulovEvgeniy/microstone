<template>
    <div class="input-row">
      <div class="input-row__container" 
        :class="{'input-row__container_active': inFocus, 'input-row__container_filled': isFilled, 'input-row__container_invalid': this.invalid}"
      >
        <span v-if="maskText && (isFilled || inFocus)" class="input-row__holder">
          <span>{{firstPart}}</span>{{lastPart}}
        </span>
        <input @focus="onFocus" @blur="onBlur" class="input-row__input" 
          :type="inputType" 
          :name="inputName"
          :id="inputId"
          :required="inputRequired"
          :value="valueCalc"
          @input="onInput($event)"
          @keypress="onKeypress($event)"
        >
        <label :for="inputId" class="input-row__label">{{label}}</label>
        <i v-show="showCheck" class="fa fa-check input-row__icon"></i>
      </div>
      <div class="input-row__error">
        <div class="input-row__error-message">{{reqMessage}}</div>
        <div class="input-row__error-extra" :class="{'input-row__error-extra_hidden': valMessage.length==0}">{{valMessage}}</div>
      </div>
    </div>
</template>

<script>
 
    export default {
        data() {
            return {
              inFocus: false,
              needValidate: false
            }
        },
        props: [
          'inputType',
          'inputName',
          'inputId',
          'inputRequired',
          'label',
          'inputValue',
          'validate',
          'inputMask',
          'maxInputLength',
          'maskHolder',
          'maskText'
        ],
        methods: {
          onInput(e) {
            let val = e.target.value;
            if (this.maskText) {
              let res = '';
              val = val.replace('+7', '');
              for (let i = 0; i < val.length; i++) {
                if (this.inputMask.test(val[i])) {
                  res+=val[i];
                }
              }
              val = res;
              if (!this.inputValue) {
               e.target.value = this.valueCalc; 
              }
            }
            this.$emit('change', val);
          },
          onBlur() {
            this.needValidate = true;
            this.inFocus=false
          },
          onFocus() {
            this.inFocus=true;
            if (this.isFilled) {
              this.needValidate = true;
            }
          },
          onKeypress(evt) {
            if (!this.inputMask) {
              return;
            }
            let key = !evt.charCode ? evt.which : evt.charCode;
            if (key==0 || key==8) {
              return;
            }
            let l = this.inputValue ? this.inputValue.length : 0;
            let len = this.maxInputLength ? this.maxInputLength <= l : false;
            key = String.fromCharCode(key);
            if (!this.inputMask.test(key) || len) {
              if (evt.target.selectionStart == evt.target.selectionEnd) {
                evt.preventDefault();
                return false; 
              }
            }
          }
        },
        computed: {
          isFilled() {
            return this.inputValue ? true : false
          },
          reqMessage() {
            if (this.needValidate && this.inputRequired && !this.isFilled) {
              return 'Поле обязательно для ввода.';
            }
            return '';
          },
          valMessage() {
            if (this.validate && this.needValidate && this.isFilled && !this.validate.pattern.test(this.inputValue)) {
              return this.validate.message;
            }
            return '';
          }, 
          valid() {
            return !((this.inputRequired && !this.isFilled) || (this.validate && this.isFilled && !this.validate.pattern.test(this.inputValue))) || (!this.inputRequired && !this.isFilled);
          },
          invalid() {
            return this.needValidate && !this.valid;
          },
          isHaveValidate() {
            return this.inputRequired || this.validate ? true : false;
          },
          showCheck() {
            return this.isHaveValidate && this.valid && this.isFilled;
          },
          firstPart() {
            if(!this.maskText) return '';
            let str = this.maskText;
            let l = this.inputValue ? this.inputValue.length : 0;
            let res = '';
            let count = 0;
            for (let i = 0; i < str.length; i++) {
              if (str[i]=='#') {
                if (count>=l) {
                  break;
                }
                res+=this.inputValue[count];
                count++;
                if (count>=l) {
                  break;
                }
              } else {
                res+=str[i]
              }
            }
            return res;
          },
          lastPart() {
            if(!this.maskText) return '';
            let l = this.firstPart.length;
            return this.maskHolder.substring(l);
          },
          valueCalc() {
            if (!this.maskText) {
              return this.inputValue
            }
            if (!this.isFilled && !this.inFocus) {
              return this.inputValue;
            }
            return this.firstPart;
          }
        },
      watch: {
        valid(val) {
          this.$emit('changeValid', val);
        }
      }
    }
</script>

<style lang="less">
  .input-row {
    visibility: visible !important;
    max-width: 300px;
    margin-bottom: 0;
    width: 100%;
    line-height: 1.42857;
    &__container {
      border-radius: 8px;
      display: inline-block;
      position: relative;
      width: 100%;
      height: 54px;
      background: #fff;
      border: 1px solid #e5e5e5;
      overflow: hidden;
      box-sizing: border-box;
      &_active {
        box-shadow: 0 9px 28px #d9d9d9;
      }
      &_invalid {
        border-color: #cc2e12;
      }
      &:hover {
        border-color: #d9d9d9;
        .input-row__label {
          color: #333;
        }
      }
    }
    &__input {
      position: absolute;
      top: 0;
      left: 0;
      padding: 18px 0 0 20px;
      height: 100%;
      width: 100%;
      outline: none;
      background: transparent;
      color: #4e4e4e;
      border: none;
      font-weight: bold;
      z-index: 9;
      box-sizing: border-box;
      font-size: 16px;
      font-family: inherit;
      line-height: inherit;
    }
    &__label {
      position: absolute;
      transition: top 200ms, font-size 200ms;
      top: 15px;
      left: 0;
      padding: 0 20px;
      font-size: 16px;
      color: #8c8c8c;
      letter-spacing: .3px;
      z-index: 10;
    }
    &__icon {
      color: #6ba833;
      font-size: 24px;
      position: absolute;
      right: 12px;
      top: calc(50% - 12px);
      text-align: center;
      z-index: 10;
    }
    &__error {
      margin-bottom: 10px;
      &-message {
        margin: 8px 0;
        width: 100%;
        color: #cc2e12;
        line-height: 20px;
        font-size: 14px;
        font-weight: bold;
      }
      &-extra {
        border-radius: 8px;
        width: 100%;
        padding: 12px;
        background: #ffebea;
        color: #cc2e12;
        &_hidden {
          display: none;
        }
      }
    }
    &__holder {
      position: absolute;
      top: 23px;
      left: 20px;
      font-weight: bold;
      color: #d9d9d9;
      z-index: 9;
    }
  }

  .input-row__input:focus ~ .input-row__label, .input-row__container_filled .input-row__label {
    font-size: 14px;
    top: 5px;
  }
</style>