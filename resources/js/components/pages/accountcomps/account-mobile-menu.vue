<template>
    <div class="account-mobile__menu" :class="{open: open}">
      <ul class="account-mobile__menu_list" @click="onClick()">
          <li v-for="it in menu" :key="it.id">{{it.name}}</li>
      </ul>
    </div>
</template>

<script>
    export default {
        data() {
            return {
            }
        }, 
        props: [
            'open',
            'menu'
        ],
        methods: {
          onClick() {
            this.$emit('click');
          }
        }
    }
</script>

<style lang="less">
    .account-mobile__menu {
        transition: right 0.3s ease-out 200ms;
        opacity: 1;
        display: none;
        top: 108px;
    }
    @media (max-width: 991px) {
        .account-mobile {
            &__menu {

                display: block;
                background: rgba(0, 0, 0, 0.5);
                right: -100%;
                width: 100%;
                z-index: 1000;
                border-top: none;
                position: fixed;
                bottom: 0;
                overflow: auto;
                -webkit-overflow-scrolling: touch;
                &.open {
                    right: 0;
                }
            }
    }
    }
</style>