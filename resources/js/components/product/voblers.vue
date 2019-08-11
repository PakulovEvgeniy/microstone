<template>
    <div class="w-product-voblers">
      <div class="w-product-voblers">
        <div class="vobler">Акция</div>
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
          'voblers'
        ]
    }
</script>

<style>
  .w-product-voblers, .w-review-voblers {
    position: relative;
    display: inline-block;
    max-width: 100%;
}
.w-product-voblers .vobler, .w-review-voblers .vobler {
    display: inline-block;
    padding: 5px;
    font-size: 11px;
    border-radius: 3px;
    background-color: #fff;
    margin-right: 5px;
    margin-bottom: 5px;
    position: relative;
    overflow: hidden;
    max-width: 100%;
    text-overflow: ellipsis;
    white-space: nowrap;
    text-decoration: none;
    color: #f06292;
    cursor: pointer;
}
.w-product-voblers .vobler:after, .w-review-voblers .vobler:after {
    content: ' ';
    opacity: .1;
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: #f06292;
}
.w-product-voblers .vobler:hover:after, .w-review-voblers .vobler:hover:after {
    opacity: .2;
}
</style>