<template>
  <div class="compare__actions">
    <div class="compare__groups">
      <div class="compare__group" v-for="el in compareGroups" :key = "el.id">
        <a>{{el.name}} ({{el.items.length}})</a>
      </div>
    </div>
    <div class="compare__controls">
      <ui-toggle 
        caption="Только различающиеся параметры"
      ></ui-toggle>
      <a class="clear-compare-list"><i class="fa fa-trash"></i><span>Очистить список</span></a>
    </div>
    <div class="compare__products">
      <carousel 
        :navigationEnabled="true" 
        ref="carousel" 
        :scrollPerPage="false" 
        :paginationEnabled="false"
        :mouseDrag="false"
        :touchDrag="false"
        :loop="true"
        :perPageCustom="[[767, 3],[992, 4], [1200, 5]]"
      >
        <slide>
          Slide 1 Content
        </slide>
        <slide>
          Slide 2 Content
        </slide>
        <slide>
          Slide 3 Content
        </slide>
        <slide>
          Slide 4 Content
        </slide>
        <slide>
          Slide 5 Content
        </slide>
        <slide>
          Slide 5 Content
        </slide>
        <slide>
          Slide 7 Content
        </slide>
      </carousel>
    </div>
  </div>
</template>

<script>
  import { mapGetters } from 'vuex';
  import uiToggle from '../../system/ui-toggle.vue';
  import Carousel from '../../Carousel/Carousel.vue';
  import Slide from '../../Carousel/Slide.vue';
  export default {
    data() {
        return {
          mouseDrag: true
        }
    },
    computed: {
      ...mapGetters([
        'compareGroups'
      ])
    },
    methods: {
      onDrag() {
        console.log(this.$refs.carusel.elementHandle);
        this.mouseDrag = false;
        this.$refs.carusel.owl.data(this.$refs.carusel.elementHandle).reinit({
          mouseDrag : false
        });
      },
      onDragged() {
        console.log('enddrag');
        //this.mouseDrag = true;
      }
    },
    components: {
      uiToggle,
      Carousel,
      Slide
    },
    mounted() {

    }
  }
</script>

<style lang="less">
  .compare {
    &__groups {
      display: flex;
      flex-wrap: wrap;
    }
    &__group {
      margin: 5px 20px 5px 0;
      white-space: nowrap;
      a {
        border-bottom: 1px dotted;
        color: #333;
        font-size: 16px;
        text-decoration: none;
      }
    }
    &__controls {
      background-color: transparent;
      display: flex;
      padding-top: 29px;
      justify-content: space-between;
      .clear-compare-list {
        background-color: transparent;
        font-size: 20px;
        text-decoration: none;
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
    &__products {
      .item {
        height: 150px;
      }
    }
  }
</style>