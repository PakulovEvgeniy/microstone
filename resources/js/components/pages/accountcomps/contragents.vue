<template>
    <div class="account-contragents">
      <div v-if="userContragents.length==0" class="account-contragents__info">
        Контрагенты отсутствуют  
      </div>
      <div v-else class="account-contragents__table">
        <client-only>
        <vue-good-table
          :columns="columns"
          :rows="userContragents"
        >
          <template slot="table-row" slot-scope="props">
              <div v-if="props.column.field == 'actions'" class="account-contragents__actions">
                <button class="btn btn-success" @click="onClickEdit(props.row)"><i class="fa fa-edit"></i></button>
                <button class="btn btn-danger" @click="onClickDel(props.row)"><i class="fa fa-trash"></i></button>
              </div>
              <span v-else>
                {{props.formattedRow[props.column.field]}}
              </span>
          </template>
        </vue-good-table>
        </client-only>
      </div>
      <router-link to="/account/contragents/add" type="button" class="button-ui button-ui_brand account-contragents__add">Добавить контрагента</router-link>
      <div class="account-contragents__editinfo">
        <div @click="open=!open" class="account-contragents__editinfo-header" :class="{open: open}">
          <h4>
            Как добавить контрагента
            <i class="fa" :class="{'fa-chevron-down': !open, 'fa-chevron-up' : open}"></i>
          </h4>
        </div>
        <div class="account-contragents__editinfo-content" :class="{close: !open}">
          <div class="account-contragents__editinfo-panel">
            <ul>
              <li>Можно добавить одного или нескольких контрагентов - предпринимателей, юридических или физических лиц, на которых будут выставляться счета для оплаты заказов</li>
              <li>Чтобы добавить контрагента, нажмите на кнопку "Добавить контрагента" и воспользуйтесь сервисом поиска по ИНН</li>
              <li><b>Важно!</b>  Если не будут заполнены все реквизиты, то при печати документов не будет видна информация о предприятии (строки будут пустыми) - такой документ недействителен.</li>
              <li>В случае несовпадения реквизитов, найденных сервисом, с действительными, отредактируйте их вручную.</li>
            </ul>
          </div>
        </div>
      </div>
    </div>
</template>

<script>
    import { mapGetters, mapActions } from 'vuex';
    import ClientOnly from 'vue-client-only';
    export default {
        data() {
            return {
              open: false
            }
        }, 
        props: [
        ],
        computed: {
          ...mapGetters([
           'userContragents',
           'getScreenState'
          ]),
          columns() {
           return [
                {
                  label: '',
                  field: 'actions',
                  sortable: false
                },
                {
                  label: 'Наименование',
                  field: 'name'
                },
                {
                  label: 'Вид контрагента',
                  field: 'type_text'
                },
                {
                  label: 'ИНН',
                  field: 'inn',
                  hidden: this.getScreenState == 1
                },
                {
                  label: 'КПП',
                  field: 'kpp',
                  hidden: this.getScreenState == 1
                }
              ]
          }
        },
        components: {
          ClientOnly
        },
        methods: {
          ...mapActions([
              'queryPostToServer',
          ]),
          onClickEdit(e) {
            this.$router.push('/account/contragents/edit?id='+e.id);
          },
          onClickDel(e) {
            this.$modal.show('dialog', {
              text: 'Вы действительно хотите удалить контрагента: <br><b>' + e.name + '</b>?',
              buttons: [
                {
                  title: 'ОК',
                  handler: () => { 
                    this.delContragent(e) 
                  }
                },
                {
                  title: 'Отмена'
                }
             ]
            })
          },
          delContragent(e) {
            this.$modal.hide('dialog');
            this.queryPostToServer({
              url: '/account/contragents/delete',
              params: {
                'id': e.id
              }
            });
          }
        }
    }
</script>

<style lang="less">
    .account-contragents {
      &__info {
        margin-bottom: 20px;
        padding: 15px;
        border: 1px solid transparent;
        border-radius: 4px;
        background-color: #fcf8e3;
        border-color: #faebcc;
        color: #8a6d3b;
        width: 300px;
      }
      &__add {
        padding-right: 20px;
        padding-left: 20px;
        display: inline-block;
        line-height: 40px;
        &:hover {
          text-decoration: none;
          color: #fff;
        }
      }
      &__editinfo {
        &-header {
          cursor: pointer;
          padding: 0;
          padding-top: 1.5em;
          h4 {
            margin: 0;
            font-size: 16px;
            font-weight: normal;
          }
          i {
            color: #333;
            font-size: 14px;
          }
          &:hover {
            h4, i {
              color: rgb(29, 113, 184);
            }
          }
          &.open, &.open i {
            color: rgb(29, 113, 184);
          }
        }
        &-content {
          &.close {
            display: none;
          }
        }
        &-panel {
          padding: 15px;
          ul {
            margin-top: 0;
            margin-bottom: 0;
            list-style: disc;
            padding-left: 40px;
            li {
              margin-bottom: 15px;
            }
            li:last-child {
              margin-bottom: 0;
            }
          }
        }
      }
      &__table {
        margin-bottom: 20px;
        .btn {
          font-size: 13px;
        }
      }
      &__actions {
        display: flex;
        button:first-child {
          margin-right: 5px;
        }
      }
    }

</style>