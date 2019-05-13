<template>
    <ul class="catalog-subcatalog" :class="'level-'+level">
      <li v-for="item in items">
         
      </li>
    </ul>
</template>

<script>
    export default {
        data() {
            return {
            }
        },
        props: [
          'items',
          'level'
        ],
        computed: {
          
        }
    }
</script>

<style>
  
</style>