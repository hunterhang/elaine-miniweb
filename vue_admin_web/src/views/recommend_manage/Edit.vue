<template>
<div class="animated fadeIn">
    <Row>
        <Col span="12">
        <Form ref="formItem" :model="formItem" :label-width="80" :rules="ruleValidate">
            <FormItem label="标题" prop="title">
                <Input v-model="formItem.title" placeholder="请输入" />
            </FormItem>
            <FormItem label="首行链接" prop="is_online">
                <Input v-model="formItem.link1" placeholder="例如：http://www.welaine.com/dist/#/customize?id=68" />
            </FormItem>
            <FormItem label="二行链接" prop="is_online">
                <Input v-model="formItem.link2" placeholder="例如：http://www.welaine.com/dist/#/customize?id=69" />
            </FormItem>
            <FormItem label="三行链接" prop="is_online">
                <Input v-model="formItem.link3" placeholder="例如：http://www.welaine.com/dist/#/customize?id=70" />
            </FormItem>
            <Row>
                <Form-item label="发布日期" prop="start_time">
                    <Date-picker type="date" placeholder="选择日期" format="yyyy-MM-dd" :key="formItem.start_time" :value="formItem.start_time" v-model="formItem.start_time" ></Date-picker>
                </Form-item>
                <FormItem label="是否上线" prop="is_online">
                    <RadioGroup v-model="formItem.is_online">
                        <Radio label="1">上线</Radio>
                        <Radio label="0">不上线</Radio>
                    </RadioGroup>
                </FormItem>
                <FormItem label="推荐图片" prop="img_list">
                    <div class="demo-upload-list" v-for="item in formItem.img_list">
                        <img :src="item.url">
                        <div class="demo-upload-list-cover">
                            <Icon type="ios-trash-outline" @click="handleRemove(item)"></Icon>
                        </div>
                    </div>
                    <Upload :show-upload-list="false" :default-file-list="formItem.img_list" :on-success="handleSuccess" :on-exceeded-size="handleMaxSize" :format="['png']"
                        :max-size="10240" :before-upload="handleBeforeUpload" multiple type="drag" action="http://www.welaine.com/admin/goods/upload"
                        style="display: inline-block;width:58px;">
                        <div style="width: 58px;height:58px;line-height: 58px;">
                            <Icon type="camera" size="20"></Icon>
                        </div>
                    </Upload><p>注意：请依次上传首页的图片，格式为：第一行一张，第二行两张，第三行两张，请先<a href="http://www.tinypng.com" target="_blank">压缩处理</a>。</p>
                </FormItem>
                <FormItem>
                    <i-button type="primary" @click="handleSubmit('formItem')">提交</i-button>
                    <i-button type="ghost" style="margin-left: 8px">取消</i-button>
                </FormItem>
            </Row>
        </Form>
        </Col>
        <Col span="12">
        <div v-for="(item,index) in formItem.img_list" style="text-align:center;">
            <img v-if="index==0" style="width:414px" :src="item.url" />
            <img v-if="index!=0" style="width:414px" :src="item.url" />
        </div>
        </Col>
    </Row>
</div>
</template>

<script>
export default {
    data() {
        return {
            formItem: {
                title: "",
                link1:'',
                link2:'',
                link3:'',
                start_time:"",
                is_online: "1",
                img_list:[] //上传的图片
            },
            img_prefix:"",
            ruleValidate: {
                title: [{
                    required: true,
                    type: "string",
                    message: "请填写标题",
                    trigger: "change"
                }],
                link1: [{
                    required: true,
                    type: "string",
                    message: "请填写标题",
                    trigger: "change"
                }],
                link2: [{
                    required: true,
                    type: "string",
                    message: "请填写标题",
                    trigger: "change"
                }],
                link3: [{
                    required: true,
                    type: "string",
                    message: "请填写标题",
                    trigger: "change"
                }],
                start_time: [{
                    required: true,
                    type: "date",
                    message: "请填写发布日期"
                }],
                img_list: [{
                    required: true,
                    type: "array",
                    message: "请上传图片",
                    trigger: "change"
                }]
            }
        };
    },
    mounted() {
        console.log("mounted")
        const vue = this;
        this.setInit();
    },
    methods: {
        dateFormat(val) {
            let year = val.getFullYear().toString();
            let month = val.getMonth() >= 9
                ? (val.getMonth() + 1).toString()
                : "0" + (val.getMonth() + 1);
            let date = val.getDate() >= 9
                ? val.getDate().toString()
                : "0" + val.getDate();
            return year + "-" + month + "-" + date;
        },
        addStyleListInput: function(){
            this.formItem.style_list.push({name:"",price:0});
        },
        setInit() {
            this.loadding = true;
            if(this.$route.params.id == 0)//新增
            {
                var name = "";
                if(this.$route.params.cid == 1)
                {
                    name = "定制";
                }
                this.formItem.style_list.push({name:name,price:0});
                return 0;
            }
            //编辑
            this.$store.dispatch("GetRecommendDetail", this.$route.params.id).then(detail => {
                    this.formItem.title = detail.result.title;
                    this.formItem.is_online = detail.result.is_online;
                    this.formItem.link1 = detail.result.link1;
                    this.formItem.link2 = detail.result.link2;
                    this.formItem.link3 = detail.result.link3;
                    this.formItem.start_time = detail.result.start_time;
                  
                    this.formItem.img_list.push({url:detail.result.img1});
                    var img2_arr = detail.result.img2.split("|");
                    this.formItem.img_list.push({url:img2_arr[0]});
                    this.formItem.img_list.push({url:img2_arr[1]});
                    var img3_arr = detail.result.img3.split("|");
                    this.formItem.img_list.push({url:img3_arr[0]});
                    this.formItem.img_list.push({url:img3_arr[1]});
                    //this.formItem.img_list.push({url:detail.result.main_img});
                    //this.formItem.img_list.push({url:detail.result.bg_img_list});
                   // console.log(this.formItem,detail,"fomr_item");
                });
        },
        handleSubmit(name) {
            this.$refs[name].validate(valid => {
                if (!valid) {
                    this.$Message.error("表单验证失败!");
                    return false;
                }

                this.$Modal.confirm({
                    title: '操作提示',
                    content: '<p>添加成功！</p>',
                    onOk: () => {
                        this.$router.push({
                            path: "/recommend_manage"
                        });
                    },
                    onCancel: () => {
                    }
                });
                return ;

                var params = {};
                params.title = this.formItem.title;
                params.publish_date = this.dateFormat(this.formItem.publish_date);
                params.visits = this.formItem.visits;
                params.is_online = this.formItem.is_online;
                params.style_list = this.formItem.style_list;
                params.img_list = this.formItem.img_list;
                params.id = this.$route.params.id;//新增还是修改
                params.cid = this.$route.params.cid;
                this.$store.dispatch("FormSubmitForBackend", params).then(rsp => {
                    if(rsp.ret == 0)
                    {
                        let tip = "";
                        if(rsp.result.insert_id >0)
                        {
                            tip = "新增数据成功";
                        }else{
                            tip = "更新数据成功";
                        }
                        this.$Modal.confirm({
                            title: '操作提示',
                            content: '<p>'+tip+'</p>',
                            onOk: () => {
                                this.$router.push({
                                    path: "/purchase_agent"
                                });
                            },
                            onCancel: () => {
                            }
                        });
                      }else{
                        this.$Modal.confirm({
                            title: '操作提示',
                            content: '<p>'+rsp.msg+'</p>',
                            onOk: () => {
                            },
                            onCancel: () => {
                            }
                        });
 
                    }
                }).catch(err => {
                    this.$Modal.confirm({
                            title: '操作提示',
                            content: '<p>操作异常，请稍后再试\n'+err+'</p>',
                            onOk: () => {
                            },
                            onCancel: () => {
                            }
                        });
                });

            });
       },
        handleSuccess(res) {
            this.loadding = false;
            this.$Message.info({
                content: res.msg,
                duration: 3
            });
            if(res.code == 0)
            {
                var obj = {
                    status: 1,
                    url:res.result.filepath 
                };
                this.formItem.img_list.push(obj);
            }
        },
        handleMaxSize(file){
            console.log("file too large");
            this.$Message.info({
                content: '超出文件大小限制,不能超过2M',
                duration: 3
            });
        },
        handleBeforeUpload(file_info) {
            this.loadding = true;
            if(this.formItem.img_list.length == 0)
            {
                if(file_info.name.indexOf(this.img_prefix+".") == -1)    
                {
                    this.$Message.info({
                        content: '图片名称不合规范，本期新增图片名称为:' + this.img_prefix + ".png",
                        duration: 3
                    });
                    return false;
                }
                return true;
            }
            var index = file_info.name.indexOf(this.img_prefix+"_01.");
            if(index == -1)
            {
                this.$Message.info({
                    content: '图片名称不合规范，本期新增图片名称为:' + this.img_prefix+"_01.png",
                    duration: 3
                });
                return false;
            };
            return true;
        }
    }
};
</script>

<style>
.demo-upload-list {
    display: inline-block;
    width: 60px;
    height: 60px;
    text-align: center;
    line-height: 60px;
    border: 1px solid transparent;
    border-radius: 4px;
    overflow: hidden;
    background: #fff;
    position: relative;
    box-shadow: 0 1px 1px rgba(0, 0, 0, 0.2);
    margin-right: 4px;
}

.demo-upload-list img {
    width: 100%;
    height: 100%;
}

.demo-upload-list-cover {
    display: none;
    position: absolute;
    top: 0;
    bottom: 0;
    left: 0;
    right: 0;
    background: rgba(0, 0, 0, 0.6);
}

.demo-upload-list:hover .demo-upload-list-cover {
    display: block;
}

.demo-upload-list-cover i {
    color: #fff;
    font-size: 20px;
    cursor: pointer;
    margin: 0 2px;
}
</style>
