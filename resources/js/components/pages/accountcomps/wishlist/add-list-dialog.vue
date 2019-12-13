<template>
    <div class="modal-content">
      <div class="modal-header">{{edit ? 'Редактировать' : 'Создать'}} список избранных товаров</div>
      <div class="modal-body">
        <label for="create-wishlist-input">Название списка</label>
        <div class="form-group">
          <input id="create-wishlist-input" type="text" name="title" class="form-control" :placeholder="holder" v-model="value">
        </div>
        <div class="m-buttons">
          <span @click="$emit('close')" class="m-btn m-btn-default">Отмена</span>
          <span class="m-btn m-btn-additional" @click="actRun">{{edit ? 'Сохранить' : 'Создать'}}</span>
        </div>
      </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
              value: this.edit ? this.item.name : ''
            }
        }, 
        props: [
            'holder',
            'actFunc',
            'edit',
            'item'
        ],
        methods: {
          actRun() {
            this.$emit('close');
            if (this.edit) {
              this.actFunc(this.item.id, this.value);  
            } else {
              this.actFunc(this.value);
            }
          }
        }
    }
</script>

<style lang="less">
  @import '../../../../../less/vars.less';
  .modal-content {
    padding: 20px;
    .modal-header {
      padding: 0;
      margin-bottom: 20px;
    }
    .modal-body {
      padding: 0;
      position: relative;
    }
    label {
      display: inline-block;
      max-width: 100%;
      margin-bottom: 5px;
      font-weight: normal;
      font-size: 13px;
    }
    .form-group {
      margin-bottom: 15px;
    }
    input {
      -webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,0.075);
      box-shadow: inset 0 1px 1px rgba(0,0,0,0.075);
      transition: border-color ease-in-out 0.15s box-shadow ease-in-out 0.15s ease 0s;
      display: block;
      width: 100%;
      height: 36px;
      padding: 6px 12px;
      line-height: 1.42857;
      font-size: 15px;
      color: #555;
      background-color: #fff;
      background-image: none;
      border: 1px solid #ccc;
      border-radius: 4px;
      &:focus {
        border-color: #66afe9;
        outline: 0;
        -webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,0.075), 0 0 8px rgba(102,175,233,0.6);
        box-shadow: inset 0 1px 1px rgba(0,0,0,0.075), 0 0 8px rgba(102,175,233,0.6);
      }
    }
    m-btn {
      width: calc((100% - 20px) / 2);
    }
  }
</style>