<template>
    <div class="account-addresses-add">
      <div v-if="modeEdit && !curAddressEdit">Адрес не найден</div>
      <template v-else>
        <div class="account-addresses-add__search">
          <input-row
              label='Регион / район' 
              inputId='region' 
              :inputRequired="true" 
              inputType='text'
              :inputValue="userAddress.region"
              @change="onChange('region',$event)"
              @changeValid="onChangeValid('region', $event)"
          ></input-row>
          <input-row
              label='Город / н.п.' 
              inputId='gorod' 
              :inputRequired="true" 
              inputType='text'
              :inputValue="userAddress.gorod"
              @change="onChange('gorod',$event)"
              @changeValid="onChangeValid('gorod', $event)"
          ></input-row>
          <input-row
              label='Улица' 
              inputId='street' 
              :inputRequired="true" 
              inputType='text'
              :inputValue="userAddress.street"
              @change="onChange('street',$event)"
              @changeValid="onChangeValid('street', $event)"
          ></input-row>
          <input-row
              label='Дом' 
              inputId='house' 
              :inputRequired="true" 
              inputType='text'
              :inputValue="userAddress.house"
              @change="onChange('house',$event)"
              @changeValid="onChangeValid('house', $event)"
          ></input-row>
          <input-row
              label='Квартира / офис' 
              inputId='dep' 
              :inputRequired="false" 
              inputType='text'
              :inputValue="userAddress.dep"
              @change="onChange('dep',$event)"
              @changeValid="onChangeValid('dep', $event)"
          ></input-row>
          <input-row
              label='Индекс' 
              inputId='postind' 
              :inputRequired="true"
              :inputMask="/[0-9]/"
              :maxInputLength="6" 
              inputType='text'
              :inputValue="userAddress.postind"
              @change="onChange('postind',$event)"
              @changeValid="onChangeValid('postind', $event)"
              :validate="{'pattern': /^[0-9]{6,6}$/, 'message': 'Неверный почтовый индекс.'}"
          ></input-row>
        </div>
        <button @click="addAddr" :disabled="!enableBtnAddr" class="button-ui button-ui_brand">{{modeEdit ? 'Сохранить' : 'Добавить'}}</button>
      </template>
    </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex';
import inputRow from '../../system/input-row.vue';
import radioButton from '../../system/radio-button.vue';
    export default {
        data() {
            return {
                userAddress: {
                  region: '',
                  gorod: '',
                  street: '',
                  house: '',
                  dep: '',
                  postind: ''
                },
                mount: false,
                valids: {
                  region: false,
                  gorod: false,
                  street: false,
                  house: false,
                  dep: false,
                  postind: false
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
              'userAddresses'
          ]),
          modeEdit() {
            if (this.$route.params.act && this.$route.params.act == 'edit') {
              return true;
            }
            return false;
          },
          curAddressEdit() {
            if (this.modeEdit && this.$route.query && this.$route.query.id) {
              let curAddr = this.userAddresses.find((el) => {
                return el.id == this.$route.query.id;
              });
              if (curAddr) {
                return curAddr
              }
            }
          },
          enableBtnAddr() {
            return this.mount && this.valids.region && this.valids.gorod && 
                this.valids.street && this.valids.house && this.valids.postind;
            
          }
        },
        methods: {
          ...mapActions([
              'queryPostToServer',
          ]),
          onChange(key, value) {
            this.userAddress[key] = value;
          },
          onChangeValid(key, value) {
            this.valids[key] = value;
          },
          addAddr() {
            this.queryPostToServer({
              url: this.modeEdit ? '/account/addresses/edit' : '/account/addresses/add',
              params: {
                'id': this.curAddressEdit ? this.curAddressEdit.id : '',
                'region': this.userAddress.region,
                'city': this.userAddress.gorod,
                'street': this.userAddress.street,
                'house': this.userAddress.house,
                'dep': this.userAddress.dep,
                'postind': this.userAddress.postind,
              }
            });
          },
          setSuggest() {
            let self = this;
            let $region = $("#region");
            let $city   = $("#gorod");
            let $street = $("#street");
            let $house  = $("#house");
            let token = '750d3bd09b2119cb598f59132a5ea57f30526fc5';
            function join(arr /*, separator */) {
              var separator = arguments.length > 1 ? arguments[1] : ", ";
              return arr.filter(function(n){return n}).join(separator);
            }

            function formatCity(suggestion) {
              var address = suggestion.data;
              if (address.city_with_type === address.region_with_type) {
                  return address.settlement_with_type || "";
                } else {
                  return join([
                    address.city_with_type,
                    address.settlement_with_type]);
                }
            }

            $("#region").suggestions({
              token: token,
              type: "ADDRESS",
              addon: 'spinner',
              scrollOnFocus: false,
              hint: false,
              bounds: "region-area",
              onSelect: function(suggestion) {
                self.userAddress.region = suggestion.value;
                self.userAddress.postind = suggestion.data.postal_code;
              }
            });

            $city.suggestions({
              token: token,
              type: "ADDRESS",
              addon: 'spinner',
              scrollOnFocus: false,
              hint: false,
              bounds: "city-settlement",
              constraints: $region,
              formatSelected: formatCity,
              onSelect: function(suggestion) {
                self.userAddress.gorod = suggestion.value;
                self.userAddress.postind = suggestion.data.postal_code;
                self.userAddress.region = suggestion.data.region_with_type + (suggestion.data.area_with_type ? ', ' + suggestion.data.area_with_type : '');
              }
            });

            $street.suggestions({
              token: token,
              type: "ADDRESS",
              addon: 'spinner',
              scrollOnFocus: false,
              hint: false,
              bounds: "street",
              constraints: $city,
              count: 15,
              onSelect: function(suggestion) {
                self.userAddress.street = suggestion.value;
                self.userAddress.postind = suggestion.data.postal_code;
              }
            });

            $house.suggestions({
              token: token,
              type: "ADDRESS",
              addon: 'spinner',
              scrollOnFocus: false,
              hint: false,
              noSuggestionsHint: false,
              bounds: "house",
              constraints: $street,
              onSelect: function(suggestion) {
                self.userAddress.house = suggestion.value;
                self.userAddress.postind = suggestion.data.postal_code;
              }
            });
          }
        },
        mounted() {
          this.mount = true;
          this.setSuggest();
          if (this.modeEdit && this.curAddressEdit) {
            this.userAddress.region = this.curAddressEdit.region;
            this.userAddress.gorod = this.curAddressEdit.city;
            this.userAddress.street = this.curAddressEdit.street;
            this.userAddress.house = this.curAddressEdit.house;
            this.userAddress.dep = this.curAddressEdit.dep;
            this.userAddress.postind = this.curAddressEdit.postind;
          }
        }
    }
</script>

<style  lang="less">
  .account-addresses-add {
    .input-row {
      display: block;
      max-width: 460px;
      &__container {
        overflow: unset;
      }
    }
    .suggestions-promo {
      display: none !important;
    }
    .suggestions-suggestion {
      cursor: pointer;
    }
    button {
      padding-right: 20px;
      padding-left: 20px; 
    }
  }

  @media (max-width: 991px) {
    .account-addresses-add {
      .input-row {
        display: block;
        max-width: 460px;
        width: 100%;
      }
      .suggestions-suggestions {
        width: 100% !important;
        left: 0 !important;
        top: 52px !important;
        box-shadow: 0 9px 28px #d9d9d9;
        border: 1px solid #e5e5e5;
      } 
    }
  }
</style>