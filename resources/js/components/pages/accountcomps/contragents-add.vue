<template>
    <div class="account-contragents-add">
      <div class="ui-radio ui-radio_list account-contragents-add__type">
        <span>Вид контрагента</span>
        <radio-button :checked="!userContragent.type || userContragent.type=='u'" :list="true" name="type" value="u" caption="Юридическое лицо" @input="onInput($event)"></radio-button>
        <radio-button :checked="userContragent.type=='p'" :list="true" name="type" value="p" caption="Индивидуальный предприниматель" @input="onInput($event)"></radio-button>
        <radio-button :checked="userContragent.type=='f'" :list="true" name="type" value="f" caption="Физическое лицо" @input="onInput($event)"></radio-button>
      </div>
      <div v-show="mount && userContragent.type=='f'" class="account-contragents-add__fizblock">
        <input-row
            label='Фамилия' 
            inputId='Family' 
            :inputRequired="true" 
            inputType='text'
            :inputValue="userContragent.family"
            @change="onChange('family',$event)"
            @changeValid="onChangeValid('family', $event)"
        ></input-row>
        <input-row
            label='Имя' 
            inputId='Name' 
            :inputRequired="true" 
            inputType='text'
            :inputValue="userContragent.name"
            @change="onChange('name',$event)"
            @changeValid="onChangeValid('name', $event)"
        ></input-row>
        <input-row
            label='Отчество' 
            inputId='Otchestvo' 
            :inputRequired="false" 
            inputType='text'
            :inputValue="userContragent.otchestvo"
            @change="onChange('otchestvo',$event)"
            @changeValid="onChangeValid('otchestvo', $event)"
        ></input-row>
        <button @click="addFiz" :disabled="!enableBtnFiz" class="button-ui button-ui_brand">Добавить</button>
      </div>
      <div v-show="mount && userContragent.type!='f'" class="account-contragents-add__search">
        <div class="account-contragents-add__help first">
          {{dataType.help1}}
        </div>
        <input-row
            label='Наименование' 
            inputId='fullname' 
            :inputRequired="false" 
            inputType='text'
            :inputValue="userContragent.fullname"
            @change="onChange('fullname',$event)"
        ></input-row>
        <div class="account-contragents-add__help">
          {{dataType.help2}}
        </div>
      </div>
    </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex';
import inputRow from '../../system/input-row.vue';
import radioButton from '../../system/radio-button.vue';
    export default {
        data() {
            return {
                userContragent: {
                    type: 'u',
                    family: '',
                    otchestvo: '',
                    name: '',
                    fullname: '',
                    inn: ''
                },
                mount: false,
                valids: {
                  family: false,
                  name: false,
                  otchestvo: false
                }
            }
        }, 
        props: [
        ],
        components: {
          radioButton,
          inputRow
        },
        computed: {
          ...mapGetters([
              'csrf',
              'userPersonal',
              'userContragents'
          ]),
          enableBtnFiz() {
            return this.mount && this.valids.family && this.valids.name;
          },
          dataType() {
            if (this.userContragent.type == 'u') {
              return {
                help1: 'Введите название в свободной форме, ИНН или ОГРН',
                help2: 'Например: Рога и копыта',
              }
            } else {
              return {
                help1: 'Введите ИНН или фамилию, имя, отчество предпринимателя',
                help2: 'Например: Петров Иван Семенович',
              }
            }
          }
        },
        methods: {
          ...mapActions([
              'queryPostToServer',
          ]),
          onInput(e) {
            this.userContragent.type = e.value;
          },
          onChange(key, value) {
            this.userContragent[key] = value;
          },
          onChangeValid(key, value) {
            this.valids[key] = value;
          },
          addFiz() {
            this.queryPostToServer({
              url: '/account/contragents/add',
              params: {
                'type': this.userContragent.type,
                'name': this.userContragent.name,
                'family': this.userContragent.family,
                'otchestvo': this.userContragent.otchestvo
              }
            });
          },
          findContr() {
            this.queryPostToServer({
              url: '/account/searchcontragent',
              params: {
                'type': this.userContragent.type,
                'inn': this.userContragent.inn,
                'fullname': this.userContragent.fullname
              }
            });
          }
        },
        mounted() {
          this.userContragent.name = this.userPersonal.name;
          this.userContragent.family = this.userPersonal.family;
          this.userContragent.otchestvo = this.userPersonal.otchestvo;
          this.mount = true;
        }
    }
</script>

<style  lang="less">
  .account-contragents-add {
    &__type {
      margin-bottom: 20px;
      > span {
        display: inline-block;
        padding-bottom: 10px;
      }
    }
    &__fizblock {
      button {
        padding-right: 20px;
        padding-left: 20px;
      }
    }
    &__help {
      font-size: 13px;
      color: #afafaf;
      margin-bottom: 10px;
      margin-top: -10px;
      &.first {
        font-size: 14px;
        color: #333;
      }
    }
    .input-row {
      display: block;
      max-width: 460px;
    }
  }

  @media (max-width: 991px) {
    .account-contragents-add {
      .input-row {
        display: block;
        max-width: 460px;
        width: 100%;
      } 
    }
  }
</style>