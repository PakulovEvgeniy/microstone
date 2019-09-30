export default function guest ({ next, store, from, to }){
    if(store.getters.isVerify){
        return next('/account/personal');
    }
    return true;
   }