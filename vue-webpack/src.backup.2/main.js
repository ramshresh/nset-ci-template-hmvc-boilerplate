import Vue from 'vue';
import VueRouter from 'vue-router';
import VueResource from 'vue-resource';
import App from './App.vue';

Vue.use(VueRouter);
Vue.use(VueResource);
const routes=[

];

const router = new VueRouter({
  routes:routes,
  mode:'history'
});

new Vue({
  el: '#app',
  router:router,
  //template: '<App/>',
  //components : { App }
  render: h => h(App)
})
