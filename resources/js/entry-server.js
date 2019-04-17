import app from './app'
import router from './router';

new Promise((resolve, reject) => {
  router.push(url);
  router.onReady(() => {
    var st = app.$store.state;
    //st.settings = state;
    for (var ind in state) {
      st[ind] = state[ind]
    }

    const matchedComponents = router.getMatchedComponents();
    if (!matchedComponents.length) {
      return reject({ code: 404 });
    }
    resolve(app);
  }, reject);
})
  .then(app => {
    renderVueComponentToString(app, (err, res) => {
      print(res);
      print("<script type='text/javascript'>window.__INITIAL_STATE__ = JSON.parse('"+JSON.stringify(app.$store.state)+"')</script>");
    });
  })
  .catch((err) => {
    print(err);
  });