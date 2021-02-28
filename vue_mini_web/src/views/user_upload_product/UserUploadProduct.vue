<template>
    <div>
        <div class="page_header">
            上传作品
        </div>
        <div id="index_main" class="body_width">
            <AjaxUpload url="http://www.welaine.com/Home/index/upload" v-bind:on-success="onUploadSuccess" v-bind:on-error="onUploadError"></AjaxUpload>
        </div>
        <div class="body_width margin_top2">
            <div>
                <div class="english_title_font">Pattern</div>
                <div class="item_list">
                    <div class="corner item  margin_top1" v-for="item in style_list" v-bind:class="{'black':item.id==selected_style}" @click="selected_style=item.id">{{item.name}}</div>
                </div>
            </div>
            <div class="margin_top2">
                <input type="text" style="width:100%;text-align:left;" class="corner input_line_height" placeholder=" 请输入标题" v-model="title">
            </div>
            <div class="margin_top1">
                <textarea rows="3" style="width:100%;text-align:left;" class="corner input_line_height" placeholder=" 描述，请输入设计灵感" v-model="desc"></textarea>
            </div>
            <div class="margin_top1">
                <input type="text" style="width:100%;text-align:left;" class="corner input_line_height" placeholder=" 请留下联系方式" v-model="phone">
            </div>
            <div class="margin_top1"></div>
            <div class="margin_top2 upload_button corner black" @click="submit_form">上传我的设计</div>
            <div class="margin_top2"></div>
            <Dialog v-model="isShow" v-bind:type="type" v-bind:ok_jump="on_ok_jump" v-bind:is_need_jump="is_need_jump" title="弹窗标题">
                {{alert_content}}
            </Dialog>
        </div>
    </div>
</template>
<script>
    import qs from 'qs'
    import {
        Cookie
    } from '@/components/comm.js'
    import AjaxUpload from '@/components/AjaxUpload';
    import Dialog from '@/components/WeuiDialog.vue'
    export default {
        data() {
            return {
                style_list: [],
                desc: "",
                upload_img: "",
                selected_style: 0, //选中
                title: "",
                phone: "",
                //弹窗
                isShow: false,
                alert_content: "未填写内容",
                type: false,
                on_ok_jump: "/dist/user_center_designer.html", //点击确认后跳转
                is_need_jump: false
            }
        },
        mounted: function () {
            this.getStyleList();
            this.getHistoryPhone();
        },
        methods: {
            onUploadSuccess: function (url) {
                //console.log("upload_success");
                this.upload_img = url;
                console.log(this.upload_img);
            },
            onUploadError: function (msg) {
                this.show_alert_error(msg);
            },
            getStyleList: function () {
                let vm = this;
                this.$http.get('http://www.welaine.com/Home/index/getStyleList').then((response) => {
                    if (response.data.ret == 0) {
                        var data = response.data.data;
                        for (var i in data) {
                            var obj = {};
                            obj.id = i;
                            obj.name = data[i];
                            vm.style_list.push(obj);
                        }
                    }

                }, (response) => {
                    console.log('error');
                });
            },
            getHistoryPhone() {
                let vm = this;
                var openid = Cookie.get("openid");
                this.$http.get('http://www.welaine.com/Order/index/getHistoryOrderInfo', {
                    params: {
                        openid: openid
                    }
                }).then((response) => {
                    if (response.data.ret == 0) {
                        let data = response.data.data;
                        vm.phone = data.phone;
                    }
                }, (response) => {
                    console.log('error');
                });
            },
            submit_form: function () {
                if (this.upload_img == "") {
                    this.show_alert_error("请上传图片");
                    return;
                }
                if (this.selected_style == 0) {
                    this.show_alert_error("请选择Pattern");
                    return;
                }
                if (this.title == "") {
                    this.show_alert_error("请输入标题");
                    return;
                }
                if (this.desc == "") {
                    this.show_alert_error("请输入描述");
                    return;
                }
                let vm = this;
                var openid = Cookie.get("openid");
                var nickname = Cookie.get("nickname");
                var params = {
                    img: vm.upload_img,
                    title: vm.title,
                    desc: vm.desc,
                    selected_style: vm.selected_style,
                    openid: openid,
                    phone: vm.phone,
                    nickname: nickname
                };
                this.$http({
                    method: 'post',
                    url: 'http://www.welaine.com/Home/index/submit_product_form',
                    data: qs.stringify(params)
                }).then(function (response) {
                    if (response.data.ret == 0) {
                        var msg = "我们将在24小时内审核通过您的设计，并会有设计老师与您联系，请保持手机申通。"
                        vm.show_alert(msg);
                        return;
                    }
                }).catch(function (error) {
                    console.log(error);
                });
            },
            show_alert: function (msg) {
                this.is_need_jump = true;
                this.alert_content = msg;
                this.isShow = true;
            },
            show_alert_error: function (msg) {
                this.is_need_jump = false;
                this.alert_content = msg;
                this.isShow = true;
            },

        },
        components: {
            AjaxUpload,
            Dialog
        }
    }
</script>
<style scoped>
    #index_main {
        margin: 32px auto;
        text-align: center;
    }

    .upload_button {
        width: 100%;
        height: 45px;
        margin: auto auto;
        line-height: 45px;
    }

    #text {
        text-align: center;
    }

    .item_list {
        display: flex;
        flex-wrap: wrap;
    }

    .item {
        margin-right: 20px;
        padding: 2px 10px;
        min-width: 40px;
    }
</style>