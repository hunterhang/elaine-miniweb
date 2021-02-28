<template>
<!--
    <div>
        <div class="weui-cells__title"></div>
        <div class="weui-cells weui-cells_form">
            <div class="weui-cell weui-cell_vcode">
                <div class="weui-cell__hd">
                    <label class="weui-label">手机号</label>
                </div>
                <div class="weui-cell__bd">
                    <input class="weui-input" type="tel" v-model="phone" placeholder="请输入手机号">
                </div>
                <div class="weui-cell__ft">
                    <button v-if="is_disabled" class="weui-vcode-btn" @click="get_phone_code" >{{code_tip}}</button>
                    <button v-else class="weui-vcode-btn" @click="get_phone_code" >{{code_tip}}</button>
                </div>
            </div>
            <div class="weui-cell">
                <div class="weui-cell__hd"><label class="weui-label">验证码</label></div>
                <div class="weui-cell__bd">
                    <input class="weui-input" type="number" v-model="code" pattern="[0-9]*" placeholder="请输入验证码">
                </div>
            </div>
        </div>
        <div class="weui-cells__tips"></div>
        <div class="weui-btn-area">
            <a href="javascript: ;" class="weui-btn weui-btn_primary" @click="check_phone_code">验证</a>
        </div>
        <Dialog v-model="isShow" v-bind:type="type" v-bind:is_need_jump="is_need_jump" v-bind:ok_jump="ok_jump" title="弹窗标题" >
            {{alert_content}}
        </Dialog>
    </div>
-->

<div id="login_box">
    <div id="box_w_limit">
        <div id="box_title">
        登录
        </div>
        <div id="title_border"></div>
    <div class="text_line">
        <div id="phone_input">
            <input type="tel" v-model="phone" placeholder="手机号码* " />
        </div>
        <div style="width:35%;">
            <button id="btn_get_code" @click="get_phone_code">{{code_tip}}</button>
        </div>
    </div>
    <div class="text_line">
        <input type="number" v-model="code" pattern="[0-9]*" placeholder="验证码*" />
    </div>
    <div class="text_line">
        <button id="btn_submit" @click="check_phone_code">验证</button>
    </div>
    </div>
    <Dialog v-model="isShow" v-bind:type="type" v-bind:is_need_jump="is_need_jump" v-bind:ok_jump="ok_jump" title="弹窗标题" >
        {{alert_content}}
    </Dialog>

</div>
</template>

<script>
    import Dialog from '@/components/WeuiDialog.vue'
    import {
        Cookie,
        Auth
    } from '@/components/comm.js'

    export default {
        data() {
            return {
                phone:"",
                code_tip:"获取验证码",
                code:"",
                is_disabled: false ,
                //dialog
                type: false,
                isShow: false,
                alert_content: "未填写内容",
                is_need_jump:true,
                ok_jump:"/dist/index.html?#customize"
            }
        },
        mounted: function() {
    
        },
        methods: {
            get_phone_code: function()
            {
                if(this.is_disabled == true)
                {
                    return ;
                }
              var openid = Cookie.get("openid");
                if (this.phone == "")
                {
                    this.isShow = true;
                    this.alert_content = "手机号码不能为空！";
                    this.is_need_jump = false;
                    return;
                }
              let vm = this;
              this.$http.get('http://www.welaine.com/Home/Auth/get_phone_code',
                  {
                      params: {
                          openid: openid,
                          phone: this.phone
                      }
                  }).then(function(response) {
                      if (response.data.ret == 0) {
                          var wait=60;
                          var time = function() {
                              if (wait == 0) {
                                  vm.code_tip = "获取验证码";
                                  wait = 60;
                                  vm.is_disabled = false;
                              } else {
                                  vm.code_tip = "重新发送(" + wait + ")";
                                  vm.is_disabled = true;
                                  wait--;
                                  setTimeout(function() {
                                      time()
                                  }, 1000);
                              }
                          }
                          time();
                      }
                  }).catch(function(error) {
                      console.log(error);
                  }); 
            },
            check_phone_code: function()
            {
                if(this.phone == "" || this.code == "")
                {
                    this.isShow = true;
                    this.alert_content = "验证码或手机号码不能为空！";
                    this.is_need_jump = false;
                    return;
                }
              var openid = Cookie.get("openid");
              let vm = this;
              this.$http.get('http://www.welaine.com/Home/Auth/check_phone_code',
                  {
                      params: {
                          openid: openid,
                          phone: this.phone,
                          code:this.code
                      }
                  }).then(function(response) {
                      if(response.data.ret != 0)
                      {
                            vm.is_need_jump = false;
                      }else{
                          vm.is_need_jump = true;
                      }
                      vm.isShow = true;
                      vm.alert_content = response.data.msg;
                  }).catch(function(error) {
                      console.log(error);
                  });
            }
        },
    components:{Dialog}
    }
</script>
<style>
#login_box{
    border:2px solid;
    border-radius:10px;
    width: 90%;
    background-color:white;
    border-color: white;
    margin: 50% 5%;
}    
#box_w_limit{
    width: 90%;
    margin:28px 5%;
}
#box_title{
    text-align: center;
    line-height: 40px;
    font-size: 18px;
}
#title_border{
    border-bottom: 1px solid black;
    height: 20px;
    width: 100%;
    margin-bottom: 25px;
}
.text_line{
    display:flex;
    flex-direction:row;
    margin-bottom: 30px;
}
.text_line input,button{
    line-height: 40px;
    width: 100%;
}
.text_line input{
    border-bottom-width: 1px;
    border-top-width: 0px;
    border-bottom-color: gray;
    border-left-width: 0px;
    border-right-width: 0px;
    font-size: 15px;
}
#phone_input{
    width:63%;
    padding-right: 2%;
    font-size: 15px;
}
#btn_get_code{
    width: 100%;
    font-size: 16px;
    background-color: black;
    color: white;
    border-color: black;
}
#btn_submit{
    background-color: black;
    width: 100%;
    text-align: center;
    color: white;
    font-size: 16px;
    border-color: black;
}
</style>

