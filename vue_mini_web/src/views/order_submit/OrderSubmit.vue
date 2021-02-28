<template>
    <div>
        <div>
            <div style="margin-top:3%;text-align:center;margin-bottom:2%;font-weight:bold;">
                <span style="margin-left:6%;float:left;">
                    <router-link to="customize" style="color:black;">&lt;</router-link>
                </span>
                <span style="font-size:14px;">订单确认</span>
                <span v-if="this.is_disable == false" style="margin-right:6%;float:right;" @click="modify">修改</span>
            </div>
            <div class="gray_line"></div>
        </div>
        <div class="body_width">
            <div class="corner_div margin_top2" style="height:auto;">
                <div style="margin:10px;">
                    <img v-bind:src="main_img|real_img" style="width:100%;">
                </div>
            </div>
            <Size v-bind:selected_size="selected_size"></Size>
            <Patt v-bind:type="show_type" v-bind:selected_patt="selected_patt" v-bind:patt_arr="style_list" v-bind:callback="patt_callback">
            </Patt>
            <Time1 v-if="selected_size == 5" :selected_date="date" :selected_time="time"></Time1>
            <UserAddr :openid="openid" :set_user_phone="set_user_phone_callback" :set_user_addr="set_user_addr_callback" :is_need_load="order_id == null"
                :phone="phone" :addr="addr" :is_disable="is_disable"></UserAddr>
            <div class="gray_line margin_top2"></div>
            <div class="margin_top1" style="font-weight:bold;margin-bottom:4px;text-align:left;">
                <span>商品金额</span>
                <span style="float:right;font-family:'ProximaNova-Bold';">¥{{good_price|price}}</span>
            </div>
            <div class="margin_top1" style="text-align:left;font-weight:bold;">
                <span>运费</span>
                <span style="float:right;font-family:'ProximaNova-Bold';">¥{{express_price|price}}</span>
            </div>
            <div v-if="discount_amount > 0" class="margin_top1" style="text-align:left;font-weight:bold;">
                <span>优惠卷</span>
                <span style="float:right;font-family:'ProximaNova-Bold';">¥{{discount_amount|price}}</span>
            </div>

            <div class="gray_line margin_top1" style="margin-bottom:25px;"></div>
            <div style="margin-top:0px;margin-bottom:25px;">
                <div style="margin-bottom:10px;text-align:center;font-weight:bold;">
                    Total: ¥{{total_price|price}} </div>
                <div class="corner_pay" @click="submit">
                    立即支付
                </div>
            </div>
        </div>
        <Dialog v-model="isShow" v-bind:type="type" title="弹窗标题">
            {{alert_content}}
        </Dialog>
    </div>
</template>
<script>
    import qs from 'qs'
    import Size from '@/views/order_submit/c_size.vue'
    import Time1 from '@/views/order_submit/c_time.vue'
    import Patt from '@/views/order_submit/c_pattern.vue'
    import UserAddr from '@/views/order_submit/c_user_addr.vue'
    //http://127.0.0.1:8080/dist/order_submit.html?id=17&size=5&pattern=8&date=1&time=1&show_type=1#/
    //http://127.0.0.1:8080/dist/order_submit.html?id=34&size=5&pattern=1,9&date=1&time=1&show_type=2#/
    export default {
        data() {
            return {
                show_type: 1,
                selected_size: 0,
                date: -1,
                time: -1,
                selected_patt: [],
                style_list: [],
                phone: "",
                addr: "",
                product_id: 0,

                openid: "",
                main_img: "",
                express_price: 0,
                good_price: 0,
                order_id: 0,
                //discount
                discount_amount: 0,
                discount_id: 0,
                is_disable: false, //该订单是否可以修改
                //dialog
                //弹窗
                type: false,
                isShow: false,
                alert_content: "未填写内容"
            }
        },
        beforeMount: function () {
            this.openid = this.$cookie.get("openid");
            this.order_id = this.$auth.get("order_id");
            if (this.order_id != null) {
                this.getOrderInfo(this.order_id,this.openid);
            } else {
                this.product_id = this.$auth.get("id");
                this.selected_size = parseInt(this.$auth.get("size"));
                this.date = this.$auth.get("date");
                this.time = this.$auth.get("time");
                this.selected_patt = JSON.parse(this.$auth.get("pattern"));
                
                //this.show_type = parseInt(this.$auth.get("show_type"));
                //this.getCustomizeInfo(this.product_id);
                this.getGoodsDetail(this.product_id);
            }
            this.getDiscount(this.openid);
        },
        computed: {
            total_price: function () {
                return parseInt(this.good_price) + parseInt(this.express_price) - parseInt(this.discount_amount);
            }
        },
        methods: {
            getDiscount: function (openid) {
                let self = this;
                this.$store.dispatch('GetDiscount',openid).then((response) => {
                    if (response.ret == 0) {
                        self.discount_amount = response.amount;
                        self.discount_id = response.discount_id;
                    }
                }, (response) => {
                    console.log('error');
                });

            },
            getGoodsDetail: function(id){
                let self = this;
                this.$store.dispatch('GetGoodsDetail',id).then((response) => {
                if (response.ret == 0) {
                        let data = response.result;
                        self.main_img = data.main_img;
                        self.style_list = data.style_list.split('|');
                        self.express_price = data.express_price;
                    }
                }, (response) => {
                    console.log('error');
                });
            },
            
            getOrderInfo(order_id,openid) {
                let self = this;
                var obj = {openid:openid,order_id:order_id};
                this.$store.dispatch('GetOrderInfo',obj).then((response) => {
                    if (response.ret == 0) {
                        let data = response;
                        self.main_img = data.order_info.product_img;
                        self.style_list =  data.product_info.style_list.split('|');
                        self.selected_patt = data.order_info.goods_style.split(",");
                        self.selected_size = parseInt(data.order_info.goods_size);
                        self.phone = data.order_info.phone;
                        self.addr = data.order_info.address;
                        self.product_id = data.order_info.dress_id;
                        self.express_price = data.order_info.express_price;
                        self.date = data.order_info.date;
                        self.time = data.order_info.time;
                        if (data.order_info.status == 1) {
                            self.is_disable = true;
                        }
                    }
                }, (response) => {
                    console.log('error');
                });
            },
            modify() {
                this.$router.push({
                    name: 'customize',
                    query: {id:this.product_id}
                });
            },
            patt_callback(price) {
                this.good_price = price;
            },
            set_user_phone_callback(phone) {
                this.phone = phone;
            },
            set_user_addr_callback(addr) {
                this.addr = addr;
            },
            submit() {
                let self = this;
                if (this.is_disable) {
                    this.alert_content = "该订单不可以编辑";
                    this.isShow = true;
                    return;
                }
                
                var params = {
                    img: this.main_img,
                    openid: this.openid,
                    product_id: this.product_id,
                    size: this.selected_size,
                    patt: this.selected_patt,
                    date: this.date,
                    time: this.time,
                    phone: this.phone,
                    addr: this.addr,
                    order_id: this.order_id,
                    show_type: this.show_type,
                    discount_id: this.discount_id
                };
                if (params.order_id == null) {
                    params.order_id = 0;
                }
                if (params.img == "" || params.openid == "" || params.id == "" || params.size == "" || params.patt.length ==
                    0) {
                    this.alert_content = "参数错误，请重新下单";
                    this.isShow = true;
                    return;
                }
                if (params.phone == "") {
                    this.alert_content = "请填写手机号码";
                    this.isShow = true;
                    return;
                }
                if (params.addr == "") {
                    this.alert_content = "请填写收货地址";
                    this.isShow = true;
                    return;
                }

                this.$store.dispatch('OrderSubmit',qs.stringify(params)).then((response) => {
                    if (response.ret == 0) {
                        //订单时间为2小时
                        //vm.order_id = response.data.order_id;
                        //拉取支付请求
                        self.order_id = response.order_id;

                        function onBridgeReady(pay) {
                            WeixinJSBridge.invoke('getBrandWCPayRequest', {
                                    "appId": pay.appId,
                                    "timeStamp": pay.timeStamp.toString(),
                                    "nonceStr": pay.nonceStr,
                                    "package": pay.package,
                                    "signType": pay.signType,
                                    "paySign": pay.paySign //微信签名
                                },
                                function (res) {
                                    if (res.err_msg == "get_brand_wcpay_request:ok") {
                                        setTimeout(
                                            "location.href =\"/dist/user_center.html?t=1#/buy_list\";",
                                            3000);
                                    } else {
                                        self.is_jump = true;
                                        showDialog("支付失败!");
                                    }
                                }
                            );
                        }

                        if (typeof WeixinJSBridge == "undefined") {
                            if (document.addEventListener) {
                                document.addEventListener('WeixinJSBridgeReady', onBridgeReady, false);
                            } else if (document.attachEvent) {
                                document.attachEvent('WeixinJSBridgeReady', onBridgeReady);
                                document.attachEvent('onWeixinJSBridgeReady', onBridgeReady);
                            }
                        } else {
                            onBridgeReady(response.pay);
                        }
                        return;
                    } else {
                        self.alert_content = response.msg;
                        self.isShow = true;
                        return;
                    }
                }, (response) => {
                    console.log('error');
                });
                return ; 
                this.$http({
                    method: 'post',
                    url: 'http://www.welaine.com/Order/index/submit',
                    data: qs.stringify(params)
                }).then(function (response) {
                    if (response.data.ret == 0) {
                        //订单时间为2小时
                        //vm.order_id = response.data.order_id;
                        //拉取支付请求
                        vm.order_id = response.data.order_id;

                        function onBridgeReady(pay) {
                            WeixinJSBridge.invoke('getBrandWCPayRequest', {
                                    "appId": pay.appId,
                                    "timeStamp": pay.timeStamp.toString(),
                                    "nonceStr": pay.nonceStr,
                                    "package": pay.package,
                                    "signType": pay.signType,
                                    "paySign": pay.paySign //微信签名
                                },
                                function (res) {
                                    if (res.err_msg == "get_brand_wcpay_request:ok") {
                                        setTimeout(
                                            "location.href =\"/dist/user_center.html?t=1#/buy_list\";",
                                            3000);
                                    } else {
                                        vm.is_jump = true;
                                        showDialog("支付失败!");
                                    }
                                }
                            );
                        }

                        if (typeof WeixinJSBridge == "undefined") {
                            if (document.addEventListener) {
                                document.addEventListener('WeixinJSBridgeReady', onBridgeReady, false);
                            } else if (document.attachEvent) {
                                document.attachEvent('WeixinJSBridgeReady', onBridgeReady);
                                document.attachEvent('onWeixinJSBridgeReady', onBridgeReady);
                            }
                        } else {
                            onBridgeReady(response.data.pay);
                        }
                        return;
                    } else {
                        vm.alert_content = response.data.msg;
                        vm.isShow = true;
                        return;
                    }
                })
            }
        },
        components: {
            Size,
            Time1,
            Patt,
            UserAddr
        }
    }
</script>
<style>
    .body_width {
        width: 84.6%;
        text-align: center;
        margin: auto auto;
    }

    .item_title {
        font-weight: bold;
        font-family: 'ProximaNova-Bold';
        text-align: left;
    }
</style>