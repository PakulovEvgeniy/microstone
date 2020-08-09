<template>
    <div class="account-addresses">
      <div v-if="userAddresses.length==0" class="account-contragents__info">
        Адреса доставки отсутствуют  
      </div>
      <div v-else class="account-addresses__table">
        <client-only>
        <vue-good-table
          :columns="columns"
          :rows="userAddresses"
        >
          <template slot="table-row" slot-scope="props">
              <div v-if="props.column.field == 'actions'" class="account-addresses__actions">
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
      <router-link to="/account/addresses/add" type="button" class="button-ui button-ui_brand account-contragents__add">Добавить адрес</router-link>
    </div>
</template>

<script>
    import { mapGetters, mapActions } from 'vuex';
    import ClientOnly from 'vue-client-only';
    export default {
        data() {
            return {
              columns: [
                {
                  label: '',
                  field: 'actions',
                  sortable: false
                },
                {
                  label: 'Адрес',
                  field: 'address'
                }
              ]
            }
        }, 
        props: [
        ],
        computed: {
          ...mapGetters([
           'userAddresses',
           'getScreenState'
          ])
        },
        components: {
          ClientOnly
        },
        methods: {
          ...mapActions([
              'queryPostToServer',
          ]),
          onClickEdit(e) {
            this.$router.push('/account/addresses/edit?id='+e.id);
          },
          onClickDel(e) {
            this.$modal.show('dialog', {
              text: 'Вы действительно хотите удалить адрес: <br><b>' + e.address + '</b>?',
              buttons: [
                {
                  title: 'ОК',
                  handler: () => { 
                    this.delAddress(e) 
                  }
                },
                {
                  title: 'Отмена'
                }
             ]
            })
          },
          delAddress(e) {
            this.$modal.hide('dialog');
            this.queryPostToServer({
              url: '/account/addresses/delete',
              params: {
                'id': e.id
              }
            });
          }
        }
    }
</script>

<style lang="less">
  .account-addresses {
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