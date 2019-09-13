import App from './components/App.vue';
import Vue from 'vue';
import router from './router';
import {createStore} from './store';
import VTooltip from 'v-tooltip';
import Subcategory from './components/product/subcategory.vue';
import Vue2TouchEvents from 'vue2-touch-events'

var store = createStore();

var VsNotify =
{
 install: function(Vue)
 {
 	var self = this; this.g = {};

	var $notify = function(group, text, type, time){ if(self.g[group]) self.g[group](text, type, time); };

	Object.defineProperty(Vue.prototype, '$notify', { get: function(){ return $notify; } });
 }
};

Vue.directive('title', {
  inserted: (el, binding) => document.title = binding.value,
  update: (el, binding) => document.title = binding.value
});

Vue.use(VsNotify);
Vue.use(VTooltip);
Vue.use(Vue2TouchEvents);
Vue.component('subcategory', Subcategory);
Vue.component('vs-notify',
{
	template:
		'<div :class="[\'vs-notify\', group]" :style="styles"><transition-group :name="trans" mode="out-in">'+
			'<div :class="it.type" v-for="it in list" :key="it.id">'+
				'<slot name="body" :class="it.type" :item="it" :close="function(){ end(it) }">'+
					'<div @click.stop="end(it)" v-html="it.text"></div>'+
				'</slot>'+
			'</div>'+
		'</transition-group></div>',

	props:
	{
		group: String, transition: String,
		position: { type: String,  default: 'top right' },
		duration: { type: Number,  default: 3000 },
		reverse:  { type: Boolean, default: false }
	},

	data: function()
	{
		var d = !this.reverse, p = this.position, t = this.transition;
		
		if(p.indexOf('bottom')+1) d = !d;

		if(!t && p.indexOf('left' )+1) t = 'ntf-left';
		if(!t && p.indexOf('right')+1) t = 'ntf-right';
		
		return{ dir:d, trans: t, list:[] }
	},

	created: function()
	{
		var ids = 1, self = this;

		VsNotify.g[this.group] = function(text, type, time)
		{
			if(text === undefined){ self.end(); return; }

			var it = { id: ids++, text: text, type: 'ntf' + (type ? ' '+type : '') };

			time = (time !== undefined ? time : self.duration);

			if(time > 0){ it.timer = setTimeout(function(){ self.end(it); }, time); }

			self.dir ? self.list.push(it) : self.list.unshift(it);
		};
	},

	//destroyed: function(){ }, // do we need it? if so - remove group from VsNotify

	computed:
	{
		styles: function()
		{
			var s = {}, pa = this.position.split(' ');

			for(var i = 0; i < pa.length; i++)
            {
            	if(pa[i] == 'center'){ s.left = s.right = 0; s.margin = 'auto'; } else if(pa[i] != '') s[pa[i]] = 0;
            }

			return s;
		}
	},

	methods:
	{
		find: function(id){ for(var i = 0; i < this.list.length; i++) if(this.list[i].id == id) return i; return -1; },

		end_no: function(n){ if(n+1){ clearTimeout(this.list[n].timer); this.list.splice(n, 1); } },

		end: function(it)
		{
			if(it === undefined){ while(this.list.length) this.end_no(0); return; } // kill all

			this.end_no(this.find(it.id));
		}
	}
});


function inLoginInterface(path) {
	let exPath = ['/login','/register','/password'];
	return exPath.findIndex((el) => {
		return path.indexOf(el) !== -1; 
	}) !== -1;
}

function findItem(items, id) {
	for (let i = 0; i<items.length ; i++) {
		let it = items[i];
		if (it.chpu == id) {
			return it;
		}
		if (it.childrens.length) {
			let itm = findItem(it.childrens, id);
			if (itm) {return itm}
		}
	}
}

router.beforeEach((to, from, next) => {
	if (inLoginInterface(to.path)) {
		store.commit('setNonVisibleMain', true)
		return next();
	} 
	if (global && global.process && global.process.env.VUE_ENV == 'server') {
		store.commit('setNonVisibleMain', false);
		return next();
	}
	if (!from.name) {
		store.commit('setNonVisibleMain', false);
		return next();
	}
	store.commit('setActiveBlock', true);
	let ta = setTimeout(() => {
		store.commit('setActiveWait', true);
	}, 200);
	Promise.all([store.dispatch('getCatalog')])
	.then((res) => {
			let arrProm = [];
			if (to.name == 'home') {
				arrProm.push(store.dispatch('getBanners'));
				arrProm.push(store.dispatch('getPopularProducts'));
			}
			if (to.name == 'category') {
				if (to.params['id']) {
					let item = findItem(store.state.catalog.items, to.params['id']);
					if (item && item.childrens.length == 0) {
						let para = {};
						Object.assign(para, to.query);
						para.chpu = to.params['id'];
						arrProm.push(store.dispatch('getProductPage', para));
						if (to.params['id'] != from.params['id']) {
							arrProm.push(store.dispatch('getOrders', to.params['id']));
							arrProm.push(store.dispatch('getGroups', to.params['id']));
							arrProm.push(store.dispatch('getFilters', to.params['id']));
							//arrProm.push(store.dispatch('getGrpDataOfCategory', to.params['id']));
						}
					}
					//store.commit('setCategoryFiltersAll',to.query);
				}
			}
			if (to.name == 'filtersCategory') {
				 //store.commit('setNonVisibleAside', true);
				 if (to.params['idF']) {
					let item = findItem(store.state.catalog.items, to.params['idF']);
					if (item && item.childrens.length == 0) {
						if (to.params['idF'] != from.params['idF']) {
							arrProm.push(store.dispatch('getFilters', to.params['idF']));
						}
					}
				 }
			}
			if (to.name == 'product') {
				//store.commit('setNonVisibleAside', true);
				if (to.params['id'] != from.params['id']) {
					arrProm.push(store.dispatch('getProduct', to.params['id']));
				}
			}
			if (to.name == 'allManufacturer') {
				arrProm.push(store.dispatch('getBrands'));
			}
			if (to.name == 'manufacturer') {
				arrProm.push(store.dispatch('getBrands'));
				arrProm.push(store.dispatch('getCurBrand', to.params['id']));
			}
			return Promise.all(arrProm);
	})
	.then((res) => {
		store.commit('setNonVisibleMain', false);
		if (to.path.indexOf('/category') != -1) {
			if (to.params['id'] || to.params['idF']) {
				store.commit('setCategoryFiltersAll',to.query);
			}
		} else if (to.name == 'allManufacturer') {
			if (to.query && to.query.page !== undefined) {
				store.commit('setPageManuf', +to.query.page)
			};
		}
		clearTimeout(ta);
		store.commit('setActiveWait', {'wait': false, 'block': false});
		next();
	})
	.catch(e => {
		clearTimeout(ta);
        store.dispatch('showError', e);
    });
	
})

export default new Vue({
    router,
    store,
    render: h => h(App)
});