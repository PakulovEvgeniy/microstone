<template>
  <div class="list-button">
    <button
      :disabled="disable"
      @click="addToList(item.id)"
      class="button-ui button-ui_white button-ui_icon"
      :class="{
        'button-ui_done': isInList, 
        'button-ui_action-icon-on': disable && !isInList, 
        'button-ui_action-icon-off': disable && isInList
      }"
    >
      <i
        class="fa"
        :class="{
          [this.icon]: !disable,
          'fa-check': disable && !isInList,
          'slideDown': disable,
          'fa-trash-o': disable && isInList
        }"
      ></i>
    </button>
    <prodwish-popover @close="onClose" v-if="isWish && pcPopVis"  :list="item.characts" :elem="$el" :product="item"></prodwish-popover>
  </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex';
import prodwishPopover from '../pages/product/prodwish-popover.vue';
export default {
  data() {
    return {
      disable: false,
      pcPopVis: false
    };
  },
  components: {
    prodwishPopover
  },
  computed: {
    ...mapGetters([
      'auth',
      'isInWishAll',
      'isInWish'
    ]),
    isInList() {
      if (this.isWish) {
        return this.isInWishAll(this.item);
      } else {
        return this.list.indexOf(this.item.id) != -1;
      }
    }
  },
  props: [
    'item',
    'list',
    'authOnly',
    'delLocalAction',
    'addLocalAction',
    'delAuthAction',
    'addAuthAction',
    'icon',
    'isWish'
  ],
  methods: {
    onClose() {
      this.pcPopVis = false;
    },
    addToList(id) {
      if (this.isWish) {
        if (this.pcPopVis) {
          this.pcPopVis = false;
          return;
        }
        if (this.item.have_charact) {
          this.pcPopVis = true;
        } else {
          this.disable = true;
          setTimeout(() => {
            if (this.isInList) {
              this.$store.dispatch('delFromWishList', {id: id, characteristic: '', group_id: 0});
            } else {
              this.$store.dispatch('addToWishList', {id: id, characteristic: ''});
            }
            this.disable = false;
          } , 1000);
        }
        return;
      }
      if ((this.authOnly && !this.auth) || !this.authOnly) {
        this.disable = true;
        setTimeout(() => {
          if (this.isInList) {
            this.$store.dispatch(this.delLocalAction, id);
          } else {
            this.$store.dispatch(this.addLocalAction, id);
          }
          this.disable = false;
          this.$emit('click');
        } , 1000);
      } else {
        this.disable = true;
        setTimeout(() => {
          this.disable = false;    
        }, 1000);
        if (this.isInList) {
          this.$store.dispatch(this.delAuthAction, id);
        } else {
          this.$store.dispatch(this.addAuthAction, id);
        }
      }
    }
  }
};
</script>

<style lang="less">
@import '../../../less/vars.less';
@import '../../../less/smart-grid.less';

.md-block({
  .list-button > .prodwish-popover .prodwish-popover__cont {
    right: -40px;
    left: auto;
  }
});


.list-button {
    display: inline-block;
}
.button-ui.button-ui_action-icon-on {
    border: 1px solid @main-color;
    color: @main-color;
    i {
      font-size: 18px;
    }
    @media (min-width: 992px) {
      &:hover {
        color: #fff;
      }
    }
  }
  .button-ui.button-ui_action-icon-off {
      border: 1px solid #cc2e12 !important;
      color: #cc2e12;
    i {
      font-size: 18px;
    }
    @media (min-width: 992px) {
      &:hover {
        color: #fff;
      }
    }
  }

  .slideDown{
    animation-name: slideDown;
    -webkit-animation-name: slideDown;  
 
    animation-duration: 1s; 
    -webkit-animation-duration: 1s;
 
    animation-timing-function: ease;    
    -webkit-animation-timing-function: ease;    
 
    visibility: visible !important;                     
}
 
@keyframes slideDown {
    0% {
        transform: translateY(-100%);
    }
    50%{
        transform: translateY(8%);
    }
    65%{
        transform: translateY(-4%);
    }
    80%{
        transform: translateY(4%);
    }
    95%{
        transform: translateY(-2%);
    }           
    100% {
        transform: translateY(0%);
    }       
}
 
@-webkit-keyframes slideDown {
    0% {
        -webkit-transform: translateY(-100%);
    }
    50%{
        -webkit-transform: translateY(8%);
    }
    65%{
        -webkit-transform: translateY(-4%);
    }
    80%{
        -webkit-transform: translateY(4%);
    }
    95%{
        -webkit-transform: translateY(-2%);
    }           
    100% {
        -webkit-transform: translateY(0%);
    }   
}
</style>