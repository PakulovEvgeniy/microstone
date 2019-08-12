<template>
    <div class="image-slider owl-carousel">
      <div class="owl-wrapper-outer">
        <div class="owl-wrapper" :style="{'width': widthWrapper, 'transform': transWrapper}">
          <div class="owl-item" v-for="(el, ind) in images" :key="ind" :style="{'width': width + 'px'}">
            <div :class="[type, {'active': curPicture==ind}]" @mouseenter="onMouseEnter(ind)">
              <a class="lightbox-img">
                <img :src="el">
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
</template>

<script>
    import { mapGetters } from 'vuex';
    export default {
        data() {
            return {
              leftPict: 0
            }
        },
        props: [
          'images',
          'width',
          'type',
          'curPicture',
          'pictQty'
        ],
        computed: {
          ...mapGetters([
          ]),
          widthWrapper() {
            return this.images.length * this.width * 2 + 'px';
          },
          transWrapper() {
            return 'translate3d('+ -this.width*this.leftPict + 'px, 0px, 0px)';
          }
        },
        methods: {
            onMouseEnter(ind) {
              if (this.pictQty>1 && ind!=this.curPicture) {
                this.$emit('changePict', ind);
              }
            }
        },
        watch: {
          curPicture(val) {
            let stInd = this.leftPict;
            let endInd = stInd + this.pictQty - 1;
            if (val >= stInd && val <= endInd) {
              return;
            }
            if (val > endInd) {
              this.leftPict+=(val-endInd);
              return;
            }
            this.leftPict = val;
          }
        }
    }
</script>

<style>
  .owl-carousel {
    width: 100%;
    -ms-touch-action: pan-y;
    position: relative;
  }
  .owl-carousel .owl-wrapper-outer {
    overflow: hidden;
    position: relative;
    width: 100%;
  }
  .owl-carousel .owl-item, .owl-carousel .owl-wrapper {
    -webkit-backface-visibility: hidden;
    -moz-backface-visibility: hidden;
    -ms-backface-visibility: hidden;
    -webkit-transform: translate3d(0,0,0);
    -moz-transform: translate3d(0,0,0);
    -ms-transform: translate3d(0,0,0);
  }
  .owl-carousel .owl-wrapper {
    position: relative;
    left: 0px;
    display: block;
    transition: all 200ms ease 0s;
  }
  .lightbox-img img {
    vertical-align: middle;
    border-radius: 5px;
  }
</style>