<template>
    <div class="category-item-phone" :class="{'back': back, 'no-border': noborder}">
      <router-link v-if="link" :to="link">
          <div v-if="!right" class="category-link">
              <i class="fa fa-chevron-left"></i>
          </div>
          <div v-if="image" class="image">
              <picture><img width="35px" height="35px" :src="image"></picture>
          </div>
          <div class="caption" :class="{'right' : alignright}">
              <div class="vertical-container">
                  <span>{{name}}</span>
              </div>
          </div>
          <div v-if="right" class="category-link">
              <i class="fa fa-chevron-right"></i>
          </div>
      </router-link>
      <a v-else @click="$emit('click')">
          <div v-if="!right" class="category-link">
              <i class="fa fa-chevron-left"></i>
          </div>
          <div v-if="image" class="image">
              <picture><img width="35px" height="35px" :src="image"></picture>
          </div>
          <div class="caption" :class="{'right' : alignright}">
              <div class="vertical-container">
                  <span>{{name}}</span>
              </div>
          </div>
          <div v-if="right" class="category-link">
              <i class="fa fa-chevron-right"></i>
          </div>
      </a>
    </div>
</template>

<script>
import { mapGetters } from 'vuex';

    export default {
        data() {
            return {
              
            }
        },
        props: [
          'link',
          'image',
          'name',
          'right',
          'back',
          'alignright',
          'noborder'
        ], 
        components: {
         
        },
        computed: {
          ...mapGetters([

          ])
        },
        methods: {
         
        }
    }
</script>

<style>
  .category-item-phone {
        width: 100%;
        height: 95px;
        display: block;
        border-bottom: 1px solid #ddd;
        padding-left: 45px;
        background-color: white;
    }
    .category-item-phone.no-border {
      border-bottom: none;
    }
    .category-item-phone.back {
        background-color: #f5f4f5;
    }
    .category-item-phone a {
        color: #333;
        display: flex;
        align-items: center;
        height: 100%;
        text-decoration: none;
        width: 100%;
    }
    .category-item-phone i {
        font-size: 13px;
        margin-left: -12px;
        text-align: center;
        width: 36px;
    }
    .category-item-phone .caption, .category-item-phone .category-link {
        font-size: 13px;
    }
    .category-item-phone .caption.right {
      text-align: right;
    }
    .category-item-phone .caption2 {
        vertical-align: middle;
    }
    .category-item-phone:first-child a .caption {
        padding-left: 0;
    }
    .category-item-phone .category-link>i {
        color: #ccc;
    }
    .category-item-phone.back .category-link>i {
        color: #333;
    }
    .category-item-phone:hover, .category-item-phone:focus {
        background-color: #f6f6f6;
    }

  @media (max-width: 991px) {
    .category-item-phone {
        height: 60px;
        padding: 0 36px;
    }
    .category-item-phone .caption {
        font-size: 16px !important;
        padding: 0 13px;
        width: 100%;
    }
    .category-item-phone .caption>* {
        line-height: normal;
    }
    .category-item-phone .image {
        width: 36px;
    }
  }

  @media (max-width: 991px) and (min-width: 768px) {
    .category-item-phone .caption {
        padding-left: 15px;
    }
  }
</style>