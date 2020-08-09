import App from './components/App.vue';
import Vue from 'vue';
import router from './router';
import {createStore} from './store';
import VTooltip from 'v-tooltip';
import Subcategory from './components/pages/product/subcategory.vue';
import Vue2TouchEvents from 'vue2-touch-events';
import VueGoodTable from 'vue-good-table';
import 'vue-good-table/dist/vue-good-table.css';
import VModal from 'vue-js-modal/dist/ssr.index';
import 'vue-js-modal/dist/styles.css';

 

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
Vue.use(VueGoodTable);
Vue.use(VModal, { dialog: true, dynamic: true, dynamicDefaults: { clickToClose: false } });

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


function inLoginInterface(name) {
	let exPath = ['login','register','passwordLink', 'passwordReset', 'emailVerify'];
	let elem =  exPath.find((el) => {
		return el == name; 
	});
	return elem !== undefined;
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
	if (global && global.process) {
		store.commit('setNonVisibleMain', inLoginInterface(to.name));
		return next();
	}
	if (!from.name) {
		store.commit('setNonVisibleMain', inLoginInterface(to.name));
		return next();
	}

	if (to.meta.middleware) {
		const middleware = to.meta.middleware;
		const context = {
        	to,
        	from,
        	next,
			store,
			'exclude': to.meta.excludePath
		}
		
		let res = false;
		for (let i = 0; i < middleware.length; i++) {
			let el = middleware[i];
			res = el({...context});
			if (res!==true) {
				break;
			}
		}

    	if (res !== true) {
    		return;
    	}
    }

	store.dispatch('showWait');
	
	Promise.all([store.dispatch('queryForApp', {
					url: '/api/products/category',
					cancash: true,
					key: 'catalog'
				})
	])
	.then((res) => {
			let arrProm = [];
			let parId;
			switch(to.name) {
				case 'home':
					arrProm.push(store.dispatch('queryForApp', {
						url: '/api/products/banners',
						cancash: true,
						key: 'banners'
					}));
					arrProm.push(store.dispatch('queryForApp', {
						url: '/api/products/popular'
					}));
					break;
				case 'category':
					if (to.params['id']) {
						let item = findItem(store.state.catalog.items, to.params['id']);
						if (item && item.childrens.length == 0) {
							let para = {};
							Object.assign(para, to.query);
							para.chpu = to.params['id'];
							arrProm.push(store.dispatch('queryForApp', {
								url: '/api/products/productpage',
								params: para
							}));
							if (to.params['id'] != from.params['id']) {
								arrProm.push(store.dispatch('queryForApp', {
									url: '/api/products/orders',
									params: {
										chpu: to.params['id']
									}
								}));
								arrProm.push(store.dispatch('queryForApp', {
									url: '/api/products/groups',
									params: {
										chpu: to.params['id']
									}
								}));
								arrProm.push(store.dispatch('queryForApp', {
									url: '/api/products/filters',
									params: {
										chpu: to.params['id']
									}
								}));
								//arrProm.push(store.dispatch('getGrpDataOfCategory', to.params['id']));
							}
						}
					}
					break;
				case 'filtersCategory':
					if (to.params['idF']) {
						let item = findItem(store.state.catalog.items, to.params['idF']);
						if (item && item.childrens.length == 0) {
							if (to.params['idF'] != from.params['idF']) {
								arrProm.push(store.dispatch('queryForApp', {
									url: '/api/products/filters',
									params: {
										chpu: to.params['idF']
									}
								}));
							}
						}
				  }
				  break;
				case 'product':
				  if (to.params['id'] != from.params['id']) {
						arrProm.push(store.dispatch('queryForApp', {
							url: '/api/products/product',
							params: {
								chpu: to.params['id']
							}
						}));
					}
					break;  
				case 'allManufacturer':
				  arrProm.push(store.dispatch('queryForApp', {
						url: '/api/products/brands',
						cancash: true,
						key: 'brands'
					}));
					break;
				case 'manufacturer':
				  arrProm.push(store.dispatch('queryForApp', {
						url: '/api/products/brands',
						cancash: true,
						key: 'brands'
					}));
					arrProm.push(store.dispatch('queryForApp',{
						url: '/api/products/curbrand',
				        params: {
				        	chpu: to.params['id']
				        }
					}));
					break;
				case 'account_id':
				  parId = to.params['id'];
					if (parId == 'personal') {
						arrProm.push(store.dispatch('queryForApp',{
							url: '/account/personal'
						}));
					}
					if (parId == 'contragents') {
						arrProm.push(store.dispatch('queryForApp', {
							url: '/account/contragents'
						}));
					}
					if (parId == 'addresses') {
						arrProm.push(store.dispatch('queryForApp', {
							url: '/account/addresses'
						}));
					}
					if (parId == 'wishlist') {
						if (store.state.auth) {
							arrProm.push(store.dispatch('queryForApp', {
								url: '/account/wishlist'
							}));
						}
					}
					break;
				case 'cart':
					/*if (store.state.auth) {
						arrProm.push(store.dispatch('queryPostToServer', {
							url: '/account/cart'
						}));
					}*/
					
					break;
				case 'login':
				case 'register':
				case 'passwordLink':
				case 'emailVerify':
				case 'passwordReset':
				  if (from.fullPath && from.name != 'register' 
				  		&& from.name != 'passwordLink' 
				  		&& from.name != 'passwordReset'
				  		&& from.name != 'emailVerify'
				  		&& from.name != 'not-found'
				  		&& from.name != 'login') {
				  	store.commit('setAuthFrom', from.fullPath);
				  }
					break;
				case 'account_id_act':
				  parId = to.params['id'];
					let parAct = to.params['act'];
					if (parId == 'contragents') {
						arrProm.push(store.dispatch('queryForApp', {
							url: '/account/contragents'
						}));
						if (parAct == 'add' || parAct == 'edit') {
							arrProm.push(store.dispatch('queryForApp', {
								url: '/account/personal'
							}));
						}
					}
					if (parId == 'addresses' && parAct=='edit') {
						arrProm.push(store.dispatch('queryForApp', {
							url: '/account/addresses'
						}));
					}
					if (parId == 'wishlist' && store.state.auth) {
						arrProm.push(store.dispatch('queryForApp', {
							url: '/account/wishlist/' + parAct
						}));
					}
					break;	
			}
			
			return Promise.all(arrProm);
	})
	.then((res) => {
		store.commit('setNonVisibleMain', inLoginInterface(to.name));
		if (to.path.indexOf('/category') != -1) {
			if (to.params['id'] || to.params['idF']) {
				store.commit('setCategoryFiltersAll',to.query);
			}
		} else if (to.name == 'allManufacturer') {
			if (to.query && to.query.page !== undefined) {
				store.commit('setPageManuf', +to.query.page)
			};
		}
		store.dispatch('closeWait');
		next();
	})
	.catch(e => {
        store.dispatch('showError', e);
    });
	
})

export default new Vue({
    router,
    store,
    render: h => h(App)
});