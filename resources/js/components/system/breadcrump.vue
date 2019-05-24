<template>
    <ol class="breadcrump">
      <li v-for="(it, index) in links" :key="it.id">
        <router-link v-if="index<(links.length-1)" :to="it.link">
          <span v-html="it.name"></span>
        </router-link>
        <span v-else v-html="it.name"></span>
      </li>
    </ol>
</template>

<script>
    export default {
        data() {
            return {
            }
        }, 
        props: [
          'links'
        ]
    }
</script>

<style>
 .breadcrump {
    padding-left: 0;
    padding-right: 0;
    padding-top: 8px;
    margin-top: 0;
    margin-bottom: 15px;
    border-radius: 0;
    font-size: 13px;
    color: gray;
    list-style: none;
    background-color: #f5f5f5;
  }
  .breadcrump>li {
    display: inline-block;
  }
  .breadcrump>li+li:before {
    color: gray;
    content: "> ";
    padding: 0 5px 0 8px;
  }
  .breadcrump a:hover {
    color: rgb(29, 113, 184);
    text-decoration: none;
  }
</style>