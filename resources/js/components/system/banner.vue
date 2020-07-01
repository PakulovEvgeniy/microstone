<template>
  <div class="slider-box-wrapper">
    <div class="slider-box">
      <div class="bx-wrapper">
        <div class="bx-viewport">
          <ul>
            <li :style="{'opacity': ind==curBanner ? 1 : 0, 'position': ind==curBanner ? 'static' : 'absolute'}" v-for="(it, ind) in bannerList" :key="it.id">
                <div class="slide-inner"><img ref="image" :src="it.image"></div>       
            </li>
          </ul>
        </div>
        <div class="bx-controls">
          <div class="bx-pager">
            <div v-for="(it, ind) in bannerList" :key="it.id" class="bx-pager-item">
              <a :class="{'active': ind == curBanner}" @click="onClick(ind)"></a>
            </div>
          </div>
          <div class="bx-controls-direction">
            <a @click="prev" class="bx-prev">
              <i class="fa fa-chevron-left"></i>
            </a>
            <a @click="next" class="bx-next">
              <i class="fa fa-chevron-right"></i>
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
              curBanner: 0,
              interval:undefined,
            }
        },
        props: [
          'bannerList'
        ],
        computed: {
          ...mapGetters([
          ])
        },
        mounted() {
          this.interval = setInterval(this.next, 3000);
        },
        beforeDestroy() {
          clearInterval(this.interval);
        },
        methods: {
          onClick(ind) {
            this.curBanner = ind;
          },
          prev() {
            this.curBanner--;
            if (this.curBanner<0) {
              this.curBanner = this.bannerList.length-1;
            }
          },
          next() {
            this.curBanner++;
            if (this.curBanner>=this.bannerList.length) {
              this.curBanner = 0;
            }
          }
        }
    }
</script>

<style>
  .slider-box-wrapper {
    padding-top: 10px;
  }
  .slider-box {
    overflow: hidden;
    margin-bottom: 1em;
    position: relative;
    border-radius: 3px;
    border: 1px solid #dfdfdf;
  }
  .slide-inner img {
    display: block;
    border: 0;
    width: 100%;
    height: auto;
  }
  .slider-box-wrapper .bx-wrapper {
    max-width: 100%;
  }
  .bx-viewport {
    width: 100%;
    overflow: hidden;
    position: relative;
    background-color: #fff;
  }
  .bx-viewport ul {
    position: relative;
    list-style-type: none;
  }
  .bx-viewport li {
    list-style: none;
    z-index: 0;
    transition: 2s;
    top: 0;
    left: 0;
  }
  .slider-box .bx-pager {
    position: absolute;
    z-index: 90;
    bottom: 0;
    left: 42%;
    line-height: 1em;
    background: rgba(33,33,33,0.2);
    border-top-left-radius: 4px;
    border-top-right-radius: 4px;
    padding-top: 5px;
    padding-left: 10px;
  }
  .bx-pager-item {
    display: inline-block;
  }
  .bx-pager a {
    display: block;
    height: 14px;
    margin: 0 10px 0 0;
    outline: 0 none;
    width: 14px;
    overflow: hidden;
    text-indent: -30px;
    border-radius: 50%;
    background-color: #fff;
  }
  .bx-pager a.active, .bx-pager a:hover {
     background: rgba(33,33,33,0.3); 
  }
  
  .bx-prev, .bx-next {
    position: absolute;
    top: 0;
    bottom: 0;
    color: #fff;
    opacity: .5;
    width: 30px;
  }
  .bx-prev {
    left: 0;
  }
  .bx-next {
    right: 0;
  }
  .bx-prev i, .bx-next i {
    text-align: center;
    top: 50%;
    text-decoration: none;
    width: 100%;
    position: absolute;
    color: #000;
    font-size: 90%;
    background: rgba(33,33,33,0.2);
    line-height: 40px;
    margin-top: -20px;
  }
  .bx-prev i {
    border-top-right-radius: 27px;
    border-bottom-right-radius: 27px;
    padding-right: 3px;
  }
  .bx-next i {
    border-top-left-radius: 27px;
    border-bottom-left-radius: 27px;
    padding-left: 3px;
  }
  .bx-prev:hover i, .bx-next:hover i {
    background: rgba(33,33,33,0.3);
  }

</style>