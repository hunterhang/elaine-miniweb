import { GetGoodsList } from 'api/goods'
import fetch from 'utils/fetch';

const goods = {
    state: {

    },

    mutations: {

    },

    actions: {
        GetGoodsList({commit}, where) {
            return new Promise((resolve, reject) => {
                GetGoodsList(where)
                    .then(response => {
                        resolve(response.data);
                    })
                    .catch(error => {
                        reject(error);
                    });
            });
        },
        GetIndexPageList({commit}){
            return new Promise((resolve, reject) => {
                fetch({
                    url: '/Home/index/get_list',
                    method: 'get'
                })
                .then(rsp=> {
                    resolve(rsp.data);
                })
                .catch(error =>{
                    reject(error);
                });
            });
            
        },
        GetDateList({commit}){
            return new Promise((resolve, reject) => {
                fetch({
                    url: '/Home/index/getDateList',
                    method: 'get'
                })
                .then(rsp=> {
                    resolve(rsp.data);
                })
                .catch(error =>{
                    reject(error);
                });
            });
        },
        GetSizeList({commit}){
            return new Promise((resolve, reject) => {
                fetch({
                    url: '/Home/index/getSizeList',
                    method: 'get'
                })
                .then(rsp=> {
                    resolve(rsp.data);
                })
                .catch(error =>{
                    reject(error);
                });
            });
        },
        //获取推荐
        GetTopRecommend({commit},id){
            return new Promise((resolve, reject) => {
                fetch({
                    url: '/Home/index/getRecentCustomize',
                    method: 'get',
                    params:{id:id}
                })
                .then(rsp=> {
                    resolve(rsp.data);
                })
                .catch(error =>{
                    reject(error);
                });
            });
        },

        //获取详情
        GetGoodsDetail({commit},id){
            return new Promise((resolve, reject) => {
                fetch({
                    url: '/Home/index/getDetail',
                    method: 'get',
                    params:{id:id}
                })
                .then(rsp=> {
                    resolve(rsp.data);
                })
                .catch(error =>{
                    reject(error);
                });
            });
        },
        //获取优惠卷
        GetDiscount({commit},openid)
        {
            return new Promise((resolve, reject) => {
                fetch({
                    url: '/Home/index/getDiscount',
                    method: 'get',
                    params:{openid:openid}
                })
                .then(rsp=> {
                    resolve(rsp.data);
                })
                .catch(error =>{
                    reject(error);
                });
            });
        },
        //获取订单信息
        GetOrderInfo({commit},obj)
        {
            //console.log("debug",order_id,openid)
            return new Promise((resolve, reject) => {
                fetch({
                    url: '/Order/index/getOrderInfo',
                    method: 'get',
                    params:{
                        order_id: obj.order_id,
                        openid:obj.openid
                    }
                })
                .then(rsp=> {
                    resolve(rsp.data);
                })
                .catch(error =>{
                    reject(error);
                });
            });
        },
        //订单提交
        OrderSubmit({commit},params)
        {
            return new Promise((resolve, reject) => {
                fetch({
                    url: '/Order/index/submit',
                    method: 'post',
                    data:params
                })
                .then(rsp=> {
                    resolve(rsp.data);
                })
                .catch(error =>{
                    reject(error);
                });
            });
        },
        //获取订单列表
        GetOrderList({commit},obj)
        {
            return new Promise((resolve, reject) => {
                fetch({
                    url: '/Order/index/getOrderList',
                    method: 'get',
                    params:{
                        openid:obj.openid,
                        status:obj.status //0:未支付；1：已支付
                    }
                })
                .then(rsp=> {
                    resolve(rsp.data);
                })
                .catch(error =>{
                    reject(error);
                });
            });
        },
        //获取定制列表
        GetCustomizeList({commit},obj)
        {
            return new Promise((resolve, reject) => {
                fetch({
                    url: '/Home/index/get_customize_list',
                    method: 'get',
                    params:{
                        start: obj.begin,
                        limit: obj.size
                    }
                })
                .then(rsp=> {
                    resolve(rsp.data);
                })
                .catch(error =>{
                    reject(error);
                });
            });
        },
        //获取设计师列表
        GetDesignerList({commit},obj)
        {
            return new Promise((resolve, reject) => {
                fetch({
                    url: '/Home/index/get_designer_product_list',
                    method: 'get',
                    params:{
                        start: obj.begin,
                        limit: obj.size
                    }
                })
                .then(rsp=> {
                    resolve(rsp.data);
                })
                .catch(error =>{
                    reject(error);
                });
            });
        },
        //code换token,未使用
        CheckAuthCode({commit},code)
        {
            console.log(code,"CheckAuthCode");
            return new Promise((resolve, reject) => {
                fetch({
                    url: '/Home/Auth/check_auth_code',
                    method: 'get',
                    params:{
                        code: code
                    }
                })
                .then(rsp=> {
                    resolve(rsp.data);
                })
                .catch(error =>{
                    reject(error);
                });
            });
            
        },
        DelOrder({commit},params)
        {
            return new Promise((resolve, reject) => {
                fetch({
                    url: '/Order/index/del',
                    method: 'get',
                    params:{
                        openid: params.openid,
                        order_id: params.order_id
                    }
                })
                .then(rsp=> {
                    resolve(rsp.data);
                })
                .catch(error =>{
                    reject(error);
                });
            });
            
        }
    }
};

export default goods;