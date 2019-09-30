export default function guest ({ next, store, from, to }){
    if(!store.getters.isVerify){
        return next('/email/verify');
    }
    return true;
   }