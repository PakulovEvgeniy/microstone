export default function guest ({ next, store, from, to }){
 if(store.getters.auth){
     return next({
        name: 'account'
     })
 }
 return true;
}