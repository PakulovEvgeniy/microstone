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
                    fullname: ''
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
          }
        },
        methods: {
          ...mapActions({
              showError: 'showError',
              showWait: 'showWait',
              closeWait: 'closeWait'
          }),
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
            this.showWait();
            axios.post('/account/contragents/add', {   
                'type': this.userContragent.type,
                'name': this.userContragent.name,
                'family': this.userContragent.family,
                'otchestvo': this.userContragent.otchestvo
            })
            .then(response => {
                this.closeWait();
                let dat = response.data;
                if (dat.status == 'OK') {
                  this.$notify("alert", dat.message, "success");
                  this.$store.commit('setUserContragents', dat.data);
                  this.$router.push('/account/contragents');
                }
            })
            .catch(e => {
                console.dir(e);
                this.showError(e);
            })
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