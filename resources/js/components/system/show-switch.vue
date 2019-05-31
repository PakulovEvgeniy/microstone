<template>
    <div class="show-switch" :class="{'hidden' : !visible}">
      <a @click="onClick">{{caption}}</a>
      <i class="fa" :class="shevron"></i>
    </div>
</template>

<script>
    export default {
        data() {
            return {
            }
        },
        props: [
        	'type',
        	'visible'
        ], 
        computed: {
        	caption() {
        		if (this.type=="show-more") {
        			return 'Показать еще';
        		} else if (this.type=='hide-all') {
        			return 'Скрыть все';
        		}
        		return 'Неизвестный тип';
        	},
        	shevron() {
        		if (this.type=="show-more") {
        			return 'fa-chevron-down';
        		} else if (this.type=='hide-all') {
        			return 'fa-chevron-up';
        		}
        		return '';
        	}
        },
        methods: {
        	onClick() {
        		this.$emit('clickSwitch');
        	}
        }
    }
</script>

<style>
@media (min-width: 992px) {
	.show-switch {
	    border-radius: 15px;
	    float: right;
	    display: inline-block;
	    font-size: 13px;
	    line-height: 30px;
	    margin: 5px 0;
	    padding: 0;
	    text-align: center;
	}
}
	.show-switch a {
		border-bottom: 1px dotted;
		color: #0d61af;
		transition: .3s;
    	text-decoration: none;
    	line-height: 18px;
	}
	.show-switch a:hover {
		text-decoration: none;
    	color: #fc5808;
	}
	.show-switch [class*="fa-"] {
    	color: #7e7e7e;
    	font-size: .8em;
    	margin: 5px;
	}
</style>