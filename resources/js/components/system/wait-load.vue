<template>
    <div>
        <div class="wait-load" :class="{'hidden' : !activeWait.wait}">
            <div class="wait-pict wait-pict1"></div>
            <div class="wait-pict wait-pict2"></div>
        </div>
        <div class="wait-block" :class="{'hidden' : !activeWait.block}"></div>
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
        ], 
        computed: {
        	...mapGetters([
            'activeWait'
          ])
        },
        methods: {
        	
        }
    }
</script>

<style>
    .wait-load {
        position: fixed;
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;
        width: 96px;
        height: 96px;
        margin: auto;
        background-color: rgba(0,0,0,.6);
        border-radius: 8px;
        z-index: 8001;
    }
    .wait-load.hidden {
        display: none;
    }
    .wait-load .wait-pict {
        position: absolute;
        top: 23px;
        left: 23px;
        width: 48px;
        height: 48px;
        background: url(/image/catalog/waitload.png) no-repeat 50%;
        background-size: 48px;
    }
    .wait-block {
        position: fixed;
        bottom: 0;
        right: 0;
        top: 0;
        left: 0;
        z-index: 8000;
        background: rgba(0,0,0,0);
    }
    .wait-block.hidden {
        display: none;
    }
    .wait-load .wait-pict1 {
        animation: anim_wait_load 1s infinite linear;
    }
    .wait-load .wait-pict.wait-pict1 {
        background-position: 0 0;
    }
    .wait-load .wait-pict.wait-pict2 {
        background-position: 0 -48px;
    }
    @keyframes anim_wait_load{
        0% {transform:rotate(0deg)} to {transform:rotate(-359deg)}
    }
</style>