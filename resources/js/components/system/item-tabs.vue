<template>
    <div class="item-tabs r-tabs">
      <ul class="nav nav-tabs r-tabs-nav">
        <li v-for="(it, ind) in tabs" 
          :key="it.id"
          class="r-tabs-tab"
          :class="{'active': active==ind, 'r-tabs-state-default': active!=ind}" 
        ><a @click="onClick(ind)" class="r-tabs-anchor">{{it.name}}</a></li>
      </ul>
      <div class="node-block">
        <slot></slot>
      </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
            }
        }, 
        props: [
          'tabs',
          'active'
        ],
        methods: {
          onClick(ind) {
            this.$emit("changeTab", ind)
          }
        }
    }
</script>

<style>
  .r-tabs .r-tabs-tab {
    display: inline-block;
    margin: 0;
    list-style: none;
  }
  .item-tabs {
    margin-top: 2em;
    padding: 0 !important;
  }
  .nav {
    margin-bottom: 0;
    padding-left: 0;
    list-style: none;
  }
  .nav-tabs {
    border-bottom: none;
  }
  .r-tabs .r-tabs-nav {
    margin: 0;
    padding: 0;
  }
  .nav>li, .nav>li>a {
    display: block;
    position: relative;
  }
  .nav.nav-tabs>li {
    float: left;
    background: #fafafa;
    border: solid 1px #eee;
    font-size: 14px;
    font-weight: bold;
    margin-top: 0;
    padding-bottom: 0;
    padding-top: 4px;
  }
  .nav-tabs>li.active {
    color: #0094d9;
    margin-bottom: -1px;
  }
  .nav-tabs>li.active, .nav-tabs>li.active:hover, .nav-tabs>li.active:focus {
    background: #fff;
    border-top: solid 4px #0094d9;
    border-right: solid 1px #ddd;
    border-left: solid 1px #ddd;
    border-bottom: solid 2px #fff;
    border-radius: 4px 4px 0 0;
    padding-top: 0;
  }
  .nav>li>a {
    padding: 10px 15px;
    line-height: 1.25;
  }
  .nav-tabs>li>a, .nav-tabs>li>a:hover, .nav-tabs>li>a:focus {
    border: none;
    border-radius: 0;
    color: #555;
    margin: 0;
    outline: 0;
    text-decoration: none;
  }
  .nav-tabs>li.active>a, .nav-tabs>li.active>a:focus, .nav-tabs>li.active>a:hover {
    color: #555;
    background-color: #fff;
    border: 1px solid #ddd;
    border-bottom-color: transparent;
    cursor: default;
  }
  .nav-tabs>li.active>a, .nav-tabs>li.active>a:hover, .nav-tabs>li.active>a:focus {
    color: #0094d9;
    border: none;
  }
  .nav-tabs>li:not(:first-of-type) {
    margin-left: -1px;
  }
  .item-tabs .node-block {
    border-radius: 4px;
    box-shadow: inset 0 -1px 0 0 rgba(0,0,0,0.1);
    width: 100%;
    display: block;
    border: 1px solid #ddd;
    position: relative;
    background-color: #fff;
  }
  .nav:after, .nav:before {
    content: " ";
    display: table;
  }
  .nav:after {
    clear: both;
  }
  .item-tabs .node-block {
    border-top: none;
    border-top-left-radius: 0 !important;
  }
  .nav-tabs>li:hover {
    background: #eee;
  }
</style>