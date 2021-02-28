import Vue from 'vue';
import App from './App';
import router from './router';
import store from './store';
import './mock/index.js';  // 该项目所有请求使用mockjs模拟
import './login.js' 

Vue.config.productionTip = false;
/* eslint-disable no-new */
import axios from 'axios'
Vue.prototype.$http = axios;

import '../static/css/common.css' /*引入公共样式*/
import '../static/css/weui.css'

Vue.filter('price', function(value) {
  var tmp = Math.floor(value) / 100;
  return tmp.toFixed(2);
});
Vue.filter('real_img', function(value) {
  return value.replace(/thumb_/, "");
});

import Dialog from './components/WeuiDialog.vue';
Vue.component('Dialog',Dialog)

import {Auth,Cookie} from '@/components/comm.js'
Vue.prototype.$auth = Auth;
Vue.prototype.$cookie = Cookie;
/**
import VueScroller from 'vue-scroller'
Vue.use(VueScroller)
**/
import Scroll from './components/ScrollV2'
Vue.component('Scroll',Scroll)

import ProductItem from './views/show_center/c_product_item'
Vue.component('ProductItem',ProductItem)

var vm=new Vue({
  el: '#app',
  router,
  store,
  template: '<App/>',
  components: { App }
})


