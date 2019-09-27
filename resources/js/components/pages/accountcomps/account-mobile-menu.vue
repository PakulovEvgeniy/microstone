<template>
    <div class="account-mobile__menu" :class="{open: open}">
      <ul class="account-mobile__menu_list" @click="onClick()">
          <li v-for="it in menu" :key="it.id">
            <router-link :to="it.link"><i class="fa icon" :class="it.icon"></i>{{it.name}}</router-link>
          </li>
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
        &_list {
          margin: 0;
          transition: right 200ms ease-in-out 0ms;
          width: 100%;
          margin-top: 0;
          list-style: none;
          padding: 0;
          text-align: left;
          > li {
            display: block;
            position: relative;
            border-bottom: 1px solid rgba(0, 0, 0, 0.15);
            line-height: 60px;
            text-align: center;
          }
          &::before {
            content: ' ';
            display: table;
          }
          &::after {
            content: ' ';
            display: table;
            clear: both;
          } 
        }
        &.open .account-mobile__menu_list {
          box-shadow: 0 2px 4px 0 rgba(0, 0, 0, 0.25);
          right: 0;
        }
    }
    @media (max-width: 991px) and (min-width: 768px) {
      .account-mobile__menu_list {
        width: 380px;
      }
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
                &_list {
                  float: right;
                  border-top: none;
                  background: #fff;
                  left: -100%;
                  > li {
                    float: right;
                    width: 100%;
                    border-left: none;
                    a {
                      box-shadow: none;
                      position: relative;
                      display: block;
                      width: 100%;
                      text-align: left;
                      font-weight: normal;
                      padding: 0 20px;
                      line-height: 60px;
                      font-size: 18px;
                      border: none 0;
                      &:hover {
                        color: #333;
                        background-color: #f5f5f5;
                        text-decoration: none;
                        .icon {
                          display: inline-block !important;
                        }
                      }
                      &.router-link-exact-active {
                        font-weight: bold;
                        color: #1D71B8 !important;
                      }
                    }
                    .icon {
                      font-size: 16px;
                      margin-right: 12px;
                      display: inline-block !important;
                    }
                  }
                }
            }
    }
    }
</style>