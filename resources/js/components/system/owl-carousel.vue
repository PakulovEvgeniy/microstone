<template>
    <div class="image-slider owl-carousel">
      <div class="owl-wrapper-outer">
        <div ref="slider" @pointerdown="onSwipeStart($event)" @pointerup="onSwipeEnd($event)" class="owl-wrapper" :style="{'width': widthWrapper, 'transform': transWrapper, 'transition': transition}">
          <div  class="owl-item" v-for="(el, ind) in images" :key="ind" :style="{'width': width + 'px'}">
            <div :class="[type, {'active': curPicture==ind}]" @mouseenter="onMouseEnter(ind)">
              <a class="lightbox-img">
                <img @mousedown.prevent :src="el" :style="{'max-width': width + 'px'}">
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
              leftPict: 0,
              dragStart: false,
              offset: 0,
              startX: 0,
              startFirst: 0
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
            return 'translate3d('+ (-this.width*this.leftPict+this.offset) + 'px, 0px, 0px)';
          },
          transition() {
            if (!this.dragStart) {
              return 'all 200ms ease 0s';
            } 
          }
        },
        methods: {
            onMouseEnter(ind) {
              if (this.pictQty>1 && ind!=this.curPicture) {
                this.$emit('changePict', ind);
              }
            },
            onSwipeStart(e) {

              this.dragStart = true;
              this.startX = e.clientX;
              this.startFirst = this.startX;
              this.$refs.slider.onpointermove = this.onSwipeMove;
              this.$refs.slider.setPointerCapture(e.pointerId);
            },
            onSwipeEnd(e) {
              this.dragStart = false;
              this.$refs.slider.onpointermove = null;
              this.$refs.slider.releasePointerCapture(e.pointerId);
              let offset = e.clientX - this.startFirst;
              if (offset>9) {
                this.offset = 0;
                this.$emit('changePict', Math.max(this.curPicture-1,0));
              } else if (offset<-9){
                this.offset = 0;
                this.$emit('changePict', Math.min(this.curPicture+1, this.images.length-1));
              } else {
                this.offset = 0;
                this.onClick();
              }
            },
            onSwipeMove(e) {
              let offs = e.clientX - this.startX;
              this.startX = e.clientX;
              this.offset = this.offset + offs;
            },
            onClick() {
              this.$emit('clickPicture');
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
  }
  .lightbox-img img {
    vertical-align: middle;
    border-radius: 5px;
  }
</style>