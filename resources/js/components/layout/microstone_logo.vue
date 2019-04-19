<template>
    <div class="micro-logo">
     	<router-link to="/">
     		<div class="m-logo" title="На главную">MICROSTONE</div>
     	</router-link>
    </div>
</template>

<script>
    export default {
        data() {
            return {
            }
        }
    }
</script>

<style>
  .micro-logo {
  	margin: 15px auto;
  	text-align: center;
  }
  .micro-logo .m-logo {
  	font-family: 'DigitalMono-7';
    font-weight: bold;
    font-style: normal;
    font-size: 70px;
    color: #ccc;
    margin-top: 20%;
  }
  .micro-logo a:hover {
  	text-decoration: none;
  }
</style>