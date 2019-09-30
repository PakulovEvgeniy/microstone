export default function guest ({ next, store, from, to }){
 if(store.getters.auth){
     return next('/account/personal');
 }
 return true;
}