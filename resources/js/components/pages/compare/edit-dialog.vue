<template>
    <div class="modal-edit-compare">
      <div class="control-container">
        <div class="button-container">
          <div class="group">{{curGroup.name}} <span>({{curGroup.items.length}})</span></div>
          <div class="close"><i class="fa fa-times"><a @click="close">Закрыть</a></i></div>
        </div>
        <a @click="clearGroup" class="clear-compare-list"><i class="fa fa-trash"></i><span>Очистить список</span></a>
      </div>
      
      <product-compare-mobile 
        v-for="el in curGroup.items" 
        :key="el.id"
        :product="el"
        @onZoom="clickZoom($event)"
      ></product-compare-mobile>
      <v-dialog/>
      <vue-gallery :images="galImages" 
        :index="indexGallery" 
        id = "blueimp-gallery-edit"
        @close="closeGallery"></vue-gallery>
    </div>
</template>

<script>
  import { mapGetters } from 'vuex';
  import productCompareMobile from './product-compare-mobile.vue';
  import VueGallery from '../../Gallery/gallery.vue'; 
    export default {
        data() {
            return {
              indexGallery: null,
              galImages: []
            }
        }, 
        props: [
        ],
        computed: {
          ...mapGetters([
            'curGroup',
            'getScreenState'
          ])
        },
        components: {
          productCompareMobile,
          VueGallery
        },
        methods: {
          clickZoom(e) {
            this.galImages = e.images;
            this.indexGallery = 0;
          },
          closeGallery() {
            if (this.indexGallery!==null) {
              this.indexGallery = null;
            }
          },
          close() {
            this.$store.commit('setBodyBlocked',false);
            this.$emit('close');
          },
          clearGroup() {
            this.$modal.show('dialog', {
              text: 'Вы действительно хотите очистить список сравнения?',
              buttons: [
                {
                  title: 'ОК',
                  handler: () => { 
                    this.$store.dispatch('clearGroup', this.curGroup);
                    this.$modal.hide('dialog');
                  }
                },
                {
                  title: 'Отмена'
                }
              ]
            })
          }
        },
        watch: {
          getScreenState(val) {
            if (val>1) {
              this.close();
            }
          },
          'curGroup.id' (val){
            this.close();
          },
          '$route' (val) {
            this.close();
          }
        }
    }
</script>

<style lang="less">
  .modal-edit-compare {
    position: fixed;
    top: 0;
    right: 0;
    bottom: 0;
    left: 0;
    background-color: #fff;
    overflow: auto;
    .control-container {
      margin-bottom: 40px;
    }
    .button-container {
      background-color: #f6f6f6;
      border-bottom: 1px solid #d8d8d8;
      height: 60px;
      vertical-align: middle;
      &:before {
        content: ' ';
        display: inline-block;
        height: 60px;
        vertical-align: middle;
      }
      div.group {
        width: 68%;
        display: inline-block;
        padding-left: 10px;
        color: #333;
        font-weight: bold;
        span {
          font-weight: normal;
        }
      }
      div.close {
        display: inline-block;
        width: 27%;
        text-align: right;
        i {
          color: #808080;
        }
        a {
          color: #333;
          font-weight: bold;
          margin-left: 10px;
          opacity: 1;
          font-size: 18px;
        }
      }
    }
    .clear-compare-list {
      float: right;
      background-color: transparent;
      font-size: 20px;
      text-decoration: none;
      white-space: nowrap;
      margin-right: 20px;
      margin-top: 10px;
      i {
        color: #afafaf;
        margin-right: 5px;
      }
      span {
        color: #333;
        line-height: 28px;
        vertical-align: top;
        font-size: 13px;
        border-bottom: 1px dotted #000;
      }
    }
  }
</style>