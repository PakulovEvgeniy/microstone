export default function auth ({ next, store, exclude, to }){
 if (exclude) {
    if (exclude.includes(to.path)) {
        return true;
    }
 }   
 if(!store.getters.auth){
     return next({
        name: 'login'
     })
 }
 return true;
}