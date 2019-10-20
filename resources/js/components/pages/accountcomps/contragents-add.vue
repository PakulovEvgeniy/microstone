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
        <div v-show="notSelect && !conSelected" class="account-contragents-add__notselect">{{notWork ? 'Сервис не доступен' : 'Подсказка не выбрана'}}, 
          <a @click="onClickHand">введите реквизиты вручную</a><span v-if="!notWork">, либо повторите поиск</span></div>
      </div>
      <div v-show="mount && userContragent.type!='f' && conSelected" class="account-contragents-add__contragent">
        <div class="hr"></div>
        <input-row
            label='Наименование' 
            inputId='Name' 
            :inputRequired="true" 
            inputType='text'
            :inputValue="userContragent.name"
            @change="onChange('name',$event)"
            @changeValid="onChangeValid('name', $event)"
        ></input-row>
        <div class="account-contragents-add__innblock">
          <input-row
              label='ИНН' 
              inputId='Inn' 
              :inputRequired="true"
              :inputMask="/[0-9]/"
              :maxInputLength="typeContr=='u' ? 10 : 12" 
              inputType='text'
              :inputValue="userContragent.inn"
              @change="onChange('inn',$event)"
              @changeValid="onChangeValid('inn', $event)"
              :validate="{'pattern': patternInn, 'message': 'Неверный ИНН.'}"
          ></input-row>
          <input-row v-show="typeContr=='u'"
              label='КПП' 
              inputId='Kpp' 
              :inputRequired="true"
              :inputMask="/[0-9]/"
              :maxInputLength="9" 
              inputType='text'
              :inputValue="userContragent.kpp"
              @change="onChange('kpp',$event)"
              @changeValid="onChangeValid('kpp', $event)"
              :validate="{'pattern': /^[0-9]{9,9}$/, 'message': 'Неверный КПП.'}"
          ></input-row>
        </div>
        <input-row v-if="false"
          label='ОКПО' 
          inputId='Okpo' 
          :inputRequired="false"
          :inputMask="/[0-9]/"
          :maxInputLength="typeContr=='u' ? 8 : 10" 
          inputType='text'
          :inputValue="userContragent.okpo"
          @change="onChange('okpo',$event)"
          @changeValid="onChangeValid('okpo', $event)"
          :validate="{'pattern': patternOkpo, 'message': 'Неверный код ОКПО.'}"
        ></input-row>
        <input-row
            label='Юридический адрес' 
            inputId='UrAddress' 
            :inputRequired="true" 
            inputType='text'
            :inputValue="userContragent.uraddress"
            @change="onChange('uraddress',$event)"
            @changeValid="onChangeValid('uraddress', $event)"
        ></input-row>
        <div class="hr"></div>
        <div class="account-contragents-add__bank-header">Банковские реквизиты</div>
        <div class="account-contragents-add__bank">
          <div class="account-contragents-add__searchbank">
            <div class="account-contragents-add__help first">
              Введите название или БИК банка
            </div>
            <input-row
                label='Банк' 
                inputId='Bank_search' 
                :inputRequired="false" 
                inputType='text'
                :inputValue="userContragent.bank"
                @change="onChange('bank',$event)"
            ></input-row>
          </div>
          <div v-show="notSelectBank && !conSelectedBank" class="account-contragents-add__notselect">{{notWork ? 'Сервис не доступен' : 'Подсказка не выбрана'}}, 
          <a @click="onClickHandBank">введите реквизиты банка вручную</a><span v-if="!notWork">, либо повторите поиск</span></div>
          <div v-show="mount && userContragent.type!='f' && conSelectedBank" class="account-contragents-add__contragent-bank">
            <div class="hr"></div>
            <input-row
              label='БИК банка' 
              inputId='Bik' 
              :inputRequired="true"
              :inputMask="/[0-9]/"
              :maxInputLength="9" 
              inputType='text'
              :inputValue="userContragent.bik"
              @change="onChange('bik',$event)"
              @changeValid="onChangeValid('bik', $event)"
              :validate="{'pattern': /^[0-9]{9,9}$/, 'message': 'Неверный БИК банка.'}"
            ></input-row>
            <input-row
              label='Наименование банка' 
              inputId='BankName' 
              :inputRequired="true" 
              inputType='text'
              :inputValue="userContragent.bankname"
              @change="onChange('bankname',$event)"
              @changeValid="onChangeValid('bankname', $event)"
            ></input-row>
            <input-row
              label='Город банка' 
              inputId='BankCity' 
              :inputRequired="true" 
              inputType='text'
              :inputValue="userContragent.bankcity"
              @change="onChange('bankcity',$event)"
              @changeValid="onChangeValid('bankcity', $event)"
            ></input-row>
            <input-row
              label='Корр. счет' 
              inputId='korr_sch' 
              :inputRequired="true"
              :inputMask="/[0-9]/"
              :maxInputLength="20" 
              inputType='text'
              :inputValue="userContragent.korr_sch"
              @change="onChange('korr_sch',$event)"
              @changeValid="onChangeValid('korr_sch', $event)"
              :validate="{'pattern': /^[0-9]{20,20}$/, 'message': 'Неверный корр. счет банка.'}"
            ></input-row>            
            <input-row
              label='Расчетный счет' 
              inputId='rasch_sch' 
              :inputRequired="true"
              :inputMask="/[0-9]/"
              :maxInputLength="20" 
              inputType='text'
              :inputValue="userContragent.rasch_sch"
              @change="onChange('rasch_sch',$event)"
              @changeValid="onChangeValid('rasch_sch', $event)"
              :validate="{'pattern': /^[0-9]{20,20}$/, 'message': 'Неверный расчетный счет.'}"
            ></input-row>
          </div>
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
                    inn: '',
                    kpp: '',
                    okpo: '',
                    uraddress: '',
                    bik: '',
                    bankname: '',
                    bankcity: '',
                    korr_sch: '',
                    rasch_sch: '',
                    bank: ''
                },
                mount: false,
                notSelect: false,
                notSelectBank: false,
                notWork: false,
                conSelected: false,
                conSelectedBank: false,
                valids: {
                  family: false,
                  name: false,
                  otchestvo: false,
                  inn: false,
                  kpp: false,
                  okpo: false,
                  uraddress: false,
                  bik: false,
                  bankname: false,
                  korr_sch: false,
                  rasch_sch: false
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
          },
          typeContr() {
            return this.userContragent.type;
          },
          patternInn() {
            return this.typeContr == 'u' ? /^[0-9]{10,10}$/ : /^[0-9]{12,12}$/
          },
          patternOkpo() {
            return this.typeContr == 'u' ? /^[0-9]{8,8}$/ : /^[0-9]{10,10}$/
          }
        },
        methods: {
          ...mapActions([
              'queryPostToServer',
          ]),
          onClickHand() {
            this.conSelected = true;
            this.userContragent.name = '';
            this.userContragent.inn = '';
            this.userContragent.kpp = '';
            this.userContragent.okpo = '';
            this.userContragent.uraddress = '';
          },
          onClickHandBank() {
            this.conSelectedBank = true;
            this.userContragent.bik = '';
            this.userContragent.bankname = '';
            this.userContragent.bankcity = '';
            this.userContragent.korr_sch = '';
            this.userContragent.rasch_sch = '';
          },
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
          setFields(suggestion) {
            this.userContragent.name = suggestion.unrestricted_value;
            this.userContragent.inn = suggestion.data.inn;
            this.userContragent.kpp = this.typeContr=='u' ? suggestion.data.kpp : '';
            this.userContragent.uraddress = suggestion.data.address.data.postal_code + ', ' + suggestion.data.address.unrestricted_value;
            this.conSelected = true;
          },
          setFieldsBank(suggestion) {
            this.userContragent.bik = suggestion.data.bic;
            this.userContragent.bankname = suggestion.data.name.payment;
            this.userContragent.bankcity = suggestion.data.payment_city;
            this.userContragent.korr_sch = suggestion.data.correspondent_account;
            this.userContragent.rasch_sch = '';
            this.conSelectedBank = true;
          },
          setSuggest() {
            let self = this;
            $("#fullname").suggestions({
              token: "750d3bd09b2119cb598f59132a5ea57f30526fc5",
              type: "PARTY",
              minChars: 3,
              addon: 'spinner',
              scrollOnFocus: false,
              params: {
                type: this.userContragent.type == 'u' ? "LEGAL" : 'INDIVIDUAL'
              },
              onSelect: function(suggestion) {
                console.log(suggestion);
                self.setFields(suggestion);
              },
              onSelectNothing: function() {
                self.notSelect = true;
              },
              onSearchError: function() {
                self.notSelect = true;
                self.notWork = true;
              }
            });
          },
          setSuggestBank() {
            let self = this;
            $("#Bank_search").suggestions({
              token: "750d3bd09b2119cb598f59132a5ea57f30526fc5",
              type: "BANK",
              minChars: 3,
              addon: 'spinner',
              scrollOnFocus: false,
              onSelect: function(suggestion) {
                console.log(suggestion);
                self.setFieldsBank(suggestion);
              },
              onSelectNothing: function() {
                self.notSelectBank = true;
              },
              onSearchError: function() {
                self.notSelectBank = true;
                self.notWork = true;
              }
            });
          }
        },
        mounted() {
          this.userContragent.name = this.userPersonal.name;
          this.userContragent.family = this.userPersonal.family;
          this.userContragent.otchestvo = this.userPersonal.otchestvo;
          this.mount = true;
          this.setSuggest(); 
          this.setSuggestBank();         
        },
        watch: {
          typeContr(val) {
            if (val=='u' || val=='p') {
              this.setSuggest();
              this.notSelect = false;
              this.notSelectBank = false;
              this.notWork = false;
              this.conSelected = false;
            } else {
              this.userContragent.name = this.userPersonal.name;
              this.userContragent.family = this.userPersonal.family;
              this.userContragent.otchestvo = this.userPersonal.otchestvo;
            }
          }
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
    &__notselect {
      a {
        color: #0094d9;
        text-decoration: none;
        &:hover {
          color: #fc8507;
          text-decoration: none;
        }
      }
    }
    .input-row {
      display: block;
      max-width: 460px;
    }
    .suggestions-promo {
      display: none !important;
    }
    .suggestions-suggestion {
      cursor: pointer;
    }
    
    &__search, &__searchbank {
      .input-row__container {
        overflow: unset;
      }
    }
    &__contragent {
      margin-top: 30px;
      .hr {
        color: #afafaf;
        background-color: #afafaf;
        border-top: 1px solid #afafaf;
        margin-bottom: 20px;
        max-width: 460px;
      }
    }
    &__innblock{
      display: flex;
      max-width: 460px;
      width: 100%;
      div:last-child {
        margin-left: 10px;
      }
    }
    &__bank-header {
      margin-bottom: 20px;
    }
  }

  @media (max-width: 991px) {
    .account-contragents-add {
      .input-row {
        display: block;
        max-width: 460px;
        width: 100%;
      }
      &__innblock{
        display: block;
        div:last-child {
          margin-left: 0;
        }
      }
      .suggestions-suggestions {
        width: 100% !important;
        left: 0 !important;
        top: 52px !important;
        box-shadow: 0 9px 28px #d9d9d9;
        border: 1px solid #e5e5e5;
        border-radius: 8px;
      } 
    }
  }
</style>