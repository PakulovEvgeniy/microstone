<template>
    <div class="menu-dropdown" :class="[{'menu-dropdown-active' : active}, add_class]">
      <i v-if="icon_before" :class="icon_before"></i>
      <a class="menu-dropdown-target" @click="onClick($event)"><slot name="activator"></slot></a>
      <i v-if="icon_after" :class="icon_after"></i>
      <ul class="menu-dropdown-items">
        <slot></slot>
      </ul>
    </div>
</template>

<script>
    export default {
        data() {
            return {
              active: false,
              curActive: null  
            }
        }, 
        props: [
          'icon_after',
          'icon_before',
          'add_class'
        ],
        methods: {
          onClick(e) {
            this.active = !this.active;
            this.curActive = e.target;
            this.$emit('input', this.active);
          },
          onClickDocument(e) {
            if(e.target !== this.curActive) {
              this.active = false;
              this.$emit('input', this.active);
            }
          }
        },
        mounted() {
          document.addEventListener('click', this.onClickDocument)
        },
        beforeDestroy() {
          document.removeEventListener('click', this.onClickDocument)
        }
    }
</script>

<style>
  .menu-dropdown {
    position: relative;
  }
  .menu-dropdown-items > li {
    display: block;
    padding: 0 15px;
    width: 100%;
    white-space: nowrap;
  }
  .menu-dropdown-items > li a {
    display: block;
  }


  .menu-dropdown-active > .menu-dropdown-items {
      display: block !important;
      opacity: 1 !important;
  }

  .menu-dropdown-items {
    display: none;
    border-radius: 3px;
    z-index: 999;
    position: absolute;
    left: calc(50% - 80px);
    padding: 0;
    min-width: 160px;
    background: #fff;
    text-align: left;
    border: 1px solid rgba(0,0,0,0.15);
    border-top-color: #ddd;
  }
  .menu-dropdown-items::before {
    content: '\A';
    position: absolute;
    top: -20px;
    left: calc(50% - 15px);
    border-width: 10px 15px;
    border-style: solid;
    border-color: transparent transparent #ddd;
  }
  .menu-dropdown-items::after {
    content: '\A';
    position: absolute;
    top: -19px;
    left: calc(50% - 15px);
    border-width: 10px 15px;
    border-style: solid;
    border-color: transparent transparent #fff;
  }
</style>