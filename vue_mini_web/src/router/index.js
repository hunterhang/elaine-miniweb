import Vue from 'vue';
import Router from 'vue-router';
const _import = require('./_import_' + process.env.NODE_ENV);
//import Full from '@/containers/Full'
//import Full2 from '@/containers/Full2'

//import Buttons from '@/views/components/Buttons'

// Views - Pages
//import Page404 from '@/views/errorPages/Page404'
//import Page500 from '@/views/errorPages/Page500'
/* login */
//const Login = _import('login/Login');
Vue.use(Router);
/**\
export const constantRouterMap = [
    { path: '/login', component: Login, hidden: true },
    {path: '/pages',redirect: '/pages/p404', name: 'Pages',
          component: {
            render (c) { return c('router-view') }
              // Full,
          },
          children: [{path: '404',  name: 'Page404', component: _import('errorPages/Page404') },
                     {path: '500',name: 'Page500',component: _import('errorPages/Page404')},
                    ]
    }
]
**/
export const constantRouterMap = [
    {
        path: '/',
        name:'index',
        component:_import('Index')
    },
    {
        path: '/shop',
        name: 'shop',
        redirect: 'designer',
        component: _import('shop/Index'),
        hidden:false,
        children:[
            {
                path:'/designer',
                name:'designer',
                component:_import('shop/Designer')
            },
            {
                path:'/customize',
                name:'customize',
                component:_import('shop/Customize')
            }
        ]
    },
    {
        path:'/order_submit',
        //path:'/order_submit/:id/:size/:pattern/:date/:time/:show_type',
        name:'order_submit',
        component:_import('order_submit/OrderSubmit'),
        hidden:false
    },
    {
        path: '/show_center',
        name: 'show_center',
        redirect: 'customize_list',
        component: _import('show_center/ShowCenter'),
        hidden:false,
        children:[
            {
                path:'/customize_list',
                name:'customize_list',
                component:_import('show_center/CustomizeList')
            },
            {
                path:'/designer_list',
                name:'designer_list',
                component:_import('show_center/DesignerList')
            },
            {
                path:'/dress_match_list',
                name:'dress_match_list',
                component:_import('show_center/DressMatchList')
            },
        ]
    },
    {
        path: '/user_center',
        name: 'user_center',
        redirect: 'pay_list',
        component: _import('user_center/UserCenter'),
        hidden:false,
        children:[
            {
                path:'/buy_list',
                name:'buy_list',
                component:_import('user_center/BuyList')
            },
            {
                path:'/pay_list',
                name:'pay_list',
                component:_import('user_center/PayList')
            }
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
export const asyncRouterMap = [{
    path: '/',
    redirect: 'index',
    name:'index'
}]

