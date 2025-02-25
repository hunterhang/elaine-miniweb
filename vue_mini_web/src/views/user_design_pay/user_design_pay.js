// The Vue build version to load with the `import` command
// (runtime-only or standalone) has been set in webpack.base.conf with an alias.
import Vue from 'vue'
import UserDesignPay from './UserDesignPay.vue'
import router from './../../router'

Vue.config.productionTip = false
/* eslint-disable no-new */
import axios from 'axios'
Vue.prototype.$http = axios;
Vue.filter('price', function(value) {
  var tmp = Math.floor(value) / 100;
  return tmp.toFixed(2);
});
Vue.filter('real_img', function(value) {
  return value.replace(/thumb_/, "");
});


new Vue({
  el: '#app',
  router,
  template: '<UserDesignPay/>',
  components: { UserDesignPay }
})
