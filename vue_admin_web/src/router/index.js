import Vue from 'vue';
import Router from 'vue-router';
const _import = require('./_import_' + process.env.NODE_ENV);
import Full from '@/containers/Full'
import Full2 from '@/containers/Full2'

import Buttons from '@/views/components/Buttons'

// Views - Pages
import Page404 from '@/views/errorPages/Page404'
import Page500 from '@/views/errorPages/Page500'


/* login */
const Login = _import('login/index');
Vue.use(Router);

export const constantRouterMap = [{
    path: '/login',
    component: Login,
    hidden: true
  },
  {
    path: '/pages',
    redirect: '/pages/p404',
    name: 'Pages',
    component: {
      render(c) {
        return c('router-view')
      }
      // Full,
    },
    children: [{
        path: '404',
        name: 'Page404',
        component: _import('errorPages/Page404')
      },
      {
        path: '500',
        name: 'Page500',
        component: _import('errorPages/Page404')
      },
    ]
  }


]

export default new Router({
  mode: 'hash',
  linkActiveClass: 'open active',
  scrollBehavior: () => ({
    y: 0
  }),
  routes: constantRouterMap
});

export const asyncRouterMap = [

  {
    path: '/',
    redirect: '/purchase_agent',
    name: '商品管理',
    component: Full,
    hidden: false,
    children: [
      {
        path: '/purchase_agent',
        name: '代卖管理',
        icon: "android-list",
        component: _import('purchase_agent/List')
      },
      {
        path: '/purchase_agent_edit/:id/:cid',
        name: '代卖编辑',
        hidden: true,
        component: _import('purchase_agent/Edit')
      },
      {
        path: '/recommend_manage',
        name: '推荐管理',
        icon: "android-list",
        component: _import('recommend_manage/List')
      },
      {
        path: '/recommend_manage_edit/:id',
        name: '首页推荐编辑',
        hidden: true,
        component: _import('recommend_manage/Edit')
      }
    ]
  },
  {
    path: '*',
    redirect: '/pages/404',
    hidden: true
  }

];