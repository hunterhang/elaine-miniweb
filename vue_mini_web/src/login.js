import router from './router'
import store from './store'
import vue from 'vue'
import NProgress from 'nprogress' // Progress 进度条
import 'nprogress/nprogress.css' // Progress 进度条样式

import {Auth,Cookie} from '@/components/comm.js'

// permissiom judge
function hasPermission(roles, permissionRoles) {
    if (roles.indexOf('admin') >= 0) return true // admin权限 直接通过
    if (!permissionRoles) return true
    return roles.some(role => permissionRoles.indexOf(role) >= 0)
}

function check_is_weixin_agent()
{
    var ua = window.navigator.userAgent.toLowerCase();
    var is_weixin = ua.match(/MicroMessenger/i) != 'micromessenger'? false:true;
    if(is_weixin == false)
    {
        var expired = 3600*24*30;
        Cookie.set("openid","test_openid",expired);
        Cookie.set("nickname","Guest",expired);
        Cookie.set("user_head","http://i1.umei.cc/uploads/tu/201611/174/29.jpg",expired);
        Cookie.set("v","v1.1",expired);
        return false;
    }
    return true;
}

function is_login()
{
    var cookie_version = "v1.1";
    var openid = Cookie.get("openid");
    var nickname = Cookie.get("nickname");
    var user_head = Cookie.get("user_head");
    var v = Cookie.get("v");
    if (openid != "" && nickname != "" && user_head != "" && v == cookie_version  ) {
      return true;
    }
      return false;
}

// register global progress.
router.beforeEach((to, from, next) => {
    NProgress.start() // 开启Progress
    if (is_login() == true) { // 判断是否有token
        next();
    } else {
        if(check_is_weixin_agent() == true)
        {
            let code = Auth.get("code");
            let state = Auth.get("state");
            if(state != null && state.indexOf("/login") !== -1 && code != undefined){
                console.log(store.state.goods);
                store.dispatch('CheckAuthCode',code).then(rsp => {
                    if(rsp.code != 0)
                    {
                        alert("系统异常，请刷新页面");
                        NProgress.done()
                        return ;
                    }
                    var names = ["openid", "nickname", "user_head", "v"];
                    var cookie_version = "v1.1";
                    Cookie.del(names);
                    if (rsp.ret == 0) {
                        var obj = {};
                        obj.openid = rsp.openid;
                        obj.nickname = rsp.nickname;
                        obj.user_head = rsp.user_head;
                        obj.v = cookie_version;
                        obj.is_first_login = rsp.is_first_login;
                        var ttl = 30;
                        Cookie.set(obj,ttl,"");
                        self.$router.push({
                            path: from.path
                        });
                    }else{
                        console.log("code is invalid!code:",code);
                    }
                }).catch(function(error) {
                    alert("系统异常，请刷新页面2");
                });
            }else{
                var url = "http://www.welaine.com/home/auth/login_by_code?to="+encodeURIComponent(to.path);
                //var _url = url.replace(/#/, "%23");
                location.href = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx66ff2fc56ab60b17&redirect_uri=" + encodeURIComponent(url) + "&response_type=code&scope=snsapi_userinfo#wechat_redirect";
                return ;
            }
            NProgress.done()
        }else{
            console.log('chrom agent');
            next({
                path: '/index'
            })
            NProgress.done() // 在hash模式下 改变手动改变hash 重定向回来 不会触发afterEach 暂时hack方案 ps：history模式下无问题，可删除该行！
        }
    }
})

router.afterEach(() => {
    NProgress.done() // 结束Progress
})