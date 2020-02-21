<template>
  <div class="modal-content to-other">
    <div @click="cancelHeader" class="modal-header"><i class="fa fa-arrow-left"></i>Выберите список</div>
    <div class="modal-body">
      <div :class="{editable: editMode===el.id}" v-for="el in listToMove" :key="el.id" >
        <div class="m-checkbox">
          <input :disabled="editMode!==false" v-model="checkList" :id="'inp-'+el.id" :value="el.id" type="checkbox">
          <label :for="'inp-'+el.id"><i class="fa fa-check"></i>{{el.name}}</label>
        </div>
        <span @click="editClick(el)" v-if="el.id" class="edit"><i class="fa fa-edit"></i></span>
        <div v-if="editMode===el.id" class="edit-block">
          <input v-model="value" type="text" :placeholder="el.name" ref="edit">
          <span @click="editMode=false" class="cancel"><i class="fa fa-times"></i></span>
          <span @click="applyClick(el)" class="apply"><i class="fa fa-check"></i></span>
        </div>
      </div>
      <div v-if="newMode" class="editable">
        <div class="m-checkbox">
          <input disabled="disabled" id="inp-new" value="new-item" type="checkbox" checked>
          <label for="inp-new"><i class="fa fa-check"></i>{{holder}}</label>
        </div>
        <div class="edit-block">
          <input v-model="value" type="text" :placeholder="holder" ref="edit">
          <span @click="cancelNewMode" class="cancel"><i class="fa fa-times"></i></span>
          <span @click="applyNewList" class="apply"><i class="fa fa-check"></i></span>
        </div>
      </div>
      <div @click="setNewMode" class="add-new" :class="{hidden: newMode}"><i class="fa fa-plus"></i>Создать новый список</div>
    </div>
    <div class="modal-footer">
      <span @click="actRun" class="submit" :class="{disabled: disable}">Копировать</span>
      <span @click="$emit('close')" class="cancel">Отменить</span>
    </div>
  </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex';
export default {
  data() {
    return {
      checkList: [],
      editMode: false,
      newMode: false,
      value: ''
    };
  },
  props: ['curGroup', 'editList', 'holder', 'addList', 'actFunc', 'product_id'],
  methods: {
    cancelHeader() {
      if (!this.smallScreen) {
        return;
      }
      this.$emit("close");
    },
    actRun() {
      if (this.disable) {
        return;
      }
      this.$emit("close");
      let par = {
        'product_id': this.product_id,
        'groups': this.checkList
      }
      this.actFunc(par);
    },
    editClick(el) {
      this.editMode = el.id;
      this.value = el.name;
      this.$nextTick(() => {
        this.focusInput();
      });
    },
    focusInput() {
      if (this.$refs['edit']) {
        if (this.newMode) {
          this.$refs['edit'].focus();  
        } else {
          this.$refs['edit'][0].focus();
        }
      }
    },
    applyClick(el) {
      this.editList(el.id, this.value);
      this.editMode = false;
    },
    setCheckNewItem(par) {
      if (+par) {
       this.checkList.push(+par); 
      }
    },
    applyNewList() {
      this.addList(this.value, this.setCheckNewItem);
      this.editMode = false;
      this.newMode = false;
    },
    setNewMode() {
      this.editMode = -1;
      this.newMode = true;
      this.value = '';
      this.$nextTick(() => {
        this.focusInput();
      });
    },
    cancelNewMode() {
      this.editMode = false;
      this.newMode = false;
    },
    close() {
      this.$store.commit('setBodyBlocked',false);
      this.$emit('close');
    }
  },
  watch: {
    '$route' (val) {
      this.close();
    }
  },
  computed: {
    ...mapGetters([
      'wishGroups',
      'smallScreen'
    ]),
    listToMove() {
      let res = [];
      res.push({
        id: 0,
        name: 'Общий список'
      });
      this.wishGroups.forEach((el) => {
        if (!el.archived) {
          res.push(el); 
        }
      });
      return res.filter((el) => {
        return el.id != this.curGroup;
      }).sort((el1, el2) => {
        return el1.id - el2.id;
      });
    },
    disable() {
      return this.editMode!==false || this.checkList.length == 0;
    }
  }
};
</script>

<style lang="less">
@import "../../../../../less/vars.less";

.modal-content.to-other {
  padding: 20px 0;
  .modal-header {
    font-size: 16px;
    padding: 0 20px;
    margin-bottom: 15px;
    font-weight: bold;
    i {
      display: none;
    }
  }
  .modal-footer {
    padding: 10px 20px 0 10px;
    span {
      border-radius: 4px;
      cursor: pointer;
      display: inline-block;
      line-height: 35px;
    }
    .submit {
      background: @hover-color;
      color: #fff;
      float: right;
      font-size: 13px;
      font-weight: bold;
      padding: 0 20px;
      &:hover {
        background: @main-color;
      }
      &.disabled {
        background: #e5e5e5;
        color: #ccc;
        cursor: not-allowed;
      }
    }
    .cancel {
      color: gray;
      padding: 0 10px;
      margin-right: 30px;
      float: right;
      &:hover {
        background: #f5f5f5;
      }
    }
    &::before {
      content: " ";
      display: table;
    }
    &::after {
      content: " ";
      display: table;
      clear: both;
    }
  }
  .modal-body {
    padding: 15px 0;
    padding-top: 0;
    max-height: 270px;
    overflow-y: auto;
    > div {
      line-height: 45px;
      padding: 0 20px;
      .m-checkbox {
        display: inline-block;
        margin: -2px 0 0;
        vertical-align: top;
        width: calc(100% - 35px);
        position: relative;
        input[type="checkbox"] + label {
          width: 100%;
          line-height: 45px;
          &::before {
            background: transparent;
            top: 14px;
          }
        }
      }
      &.add-new {
        cursor: pointer;
        font-size: 13px;
        i {
          color: #d8d8d8;
          margin-right: 12px;
          margin-left: 4px;
          vertical-align: middle;
        }
      }
      .edit, .apply, .cancel {
        cursor: pointer;
        float: right;
        height: 45px;
        display: inline-block;
        width: 35px;
        color: #999;
        text-align: center;
        &:hover {
          background: #e5e5e5;
        }
      }
      &::after {
        display: block;
        content: '';
        clear: both;
      }
      &:not(.editable):hover {
        background: #f5f5f5;
      }
      &:not(.editable):active {
        background: #e5e5e5;
      }
      .cancel {
        margin-left: 5px;
      }
      &.editable {
        .m-checkbox {
          overflow: hidden;
          width: 24px;
          input[type="checkbox"] + label {
            &::before {
              top: 16px;
            }
          }
        }
        height: 90px;
        .edit {
          display: none !important;
        }
        .edit-block {
          display: inline-block;
          margin-left: -30px;
          padding-left: 24px;
          width: 100%;
          
          input {
            border: none;
            padding: 0;
            padding-left: 5px;
            width: calc(100% - 100px);
            display: inline-block;
            outline: none;
            line-height: 45px;
            box-shadow: none;
            height: 45px;
          }
        }
      }
    }
  }
}

@media (min-width: 992px) {
  .modal-content.to-other {
    .modal-body {
      > div {
        .edit {
          display: none;
        }
        &:hover {
          .edit {
            display: inline-block;
          }
        }
      }
    }
  }
}

@media (max-width: 767px) {
  .v--modal-overlay {
    z-index: 1020 !important;
  }
  .modal-content.to-other {
    position: fixed;
    margin: 0;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    padding: 60px 0 0 0;
    z-index: 1020;
    background: #fff;
    .modal-header {
      background: #424a54;
      color: #fff;
      display: block;
      font-weight: bold;
      height: 60px;
      left: 0;
      line-height: 60px;
      padding: 0 20px;
      position: fixed;
      text-align: center;
      top: 0;
      width: 100%;
      cursor: pointer;
      i {
        display: inline-block;
        float: left;
        line-height: 60px;
      }
    }
    .modal-footer {
      background: #f6f6f6;
      border-top: 1px solid #e5e5e5;
      bottom: 0;
      height: 65px;
      padding: 15px 20px;
      position: fixed;
      width: 100%;
      z-index: 1011;
      span.cancel:hover {
        background: #e5e5e5;
      }
    }
  }
}

.m-checkbox input[type="checkbox"] {
    opacity: 0;
    margin-left: -9999px;
    position: absolute;
}

.m-checkbox input[type="checkbox"] + label {
    min-height: 18px;
    height: auto;
    line-height: 20px;
    max-width: 100%;
    overflow: hidden;
    padding-left: 26px;
    position: relative;
    vertical-align: middle;
    margin-bottom: 0;
    font-weight: normal;
    cursor: pointer;
    i {
      display: none;
    }
}

.m-checkbox input[type="checkbox"] + label::before {
  border: solid 1px #d8d8d8;
  border-radius: 2px;
  content: ' ';
  font-size: 0;
  display: block;
  background: #fff;
  height: 16px;
  left: 0;
  position: absolute;
  top: 0;
  width: 16px;
}

.m-checkbox input[type="checkbox"] + label:hover::before {
  border-color: gray;
}

.m-checkbox input[type="checkbox"]:checked + label i {
  display: inline-block;
  color: #666;
  font-size: 10px;
  position: absolute;
  top: -2px;
  left: 3px;
  line-height: 45px;
}
.editable .m-checkbox input[type="checkbox"]:checked + label i {
  top: 1px;
}

.m-checkbox input[type="checkbox"]:checked + label::before {
  border-color: @main-color;
}

.m-checkbox input[type="checkbox"][disabled] + label {
  opacity: .6;
  cursor: not-allowed;
}
</style>