<template>
<div class="animated fadeIn">
    <Row>
        <Col span="12">
        <Form ref="formItem" :model="formItem" :label-width="80" :rules="ruleValidate">
            <FormItem label="标题" prop="title">
                <Input v-model="formItem.title" placeholder="请输入" />
            </FormItem>
            <Row>
                <FormItem label="款式" prop="style_list">
                    <Row v-for="(item,index) in formItem.style_list" :key="index" style="margin-bottom:5px;">
                        <Col span="6">
                            <Input v-model="item.name" placeholder="款式名称" />
                        </Col>
                        <Col span="6">
                            <Input v-model="item.price" placeholder="请输入金额" />
                        </Col>
                        <Col span="1" v-if="index == 0">
                            <i-button type="primary" @click="addStyleListInput" style="margin-left:5px;">增加</i-button>
                        </Col>
                    </Row>
                    <!--
                    <Checkbox-group v-model="formItem.style_list">
                        <Row v-for="(item,index) in initData.style_list" :key="index" style="margin-bottom:5px;">
                            <Col span="4">
                            <Checkbox :label="item.id">{{item.name}}</Checkbox>
                            </Col>
                            <Col span="8">
                            <Input v-model="formItem.style_list_price[index]" placeholder="请输入金额" />
                            </Col>
                        </Row>
                    </Checkbox-group>
                    -->
                </FormItem>
            </Row>
            <Row>
                <Form-item label="发布日期" prop="publish_date">
                    <Date-picker type="date" placeholder="选择日期" format="yyyy-MM-dd" :key="formItem.publish_date" :value="formItem.publish_date" v-model="formItem.publish_date" ></Date-picker>
                </Form-item>
                <FormItem label="库存数" prop="stock">
                        <Input v-model="formItem.stock" placeholder="请输入" />
                    </FormItem>
                <FormItem label="浏览量" prop="visits">
                    <Input v-model="formItem.visits" placeholder="请输入" />
                </FormItem>
                <FormItem label="是否上线" prop="is_online">
                    <RadioGroup v-model="formItem.is_online">
                        <Radio label="1">上线</Radio>
                        <Radio label="0">不上线</Radio>
                    </RadioGroup>
                </FormItem>
                <FormItem label="图片上传" prop="img_list">
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
                    </Upload><p>注意：本次操作的图片名必须为:"{{img_prefix}}.png" 和 "{{img_prefix}}_01.png",图片上传前，请先<a href="http://www.tinypng.com" target="_blank">压缩处理</a>。</p>
                </FormItem>
                <FormItem>
                    <i-button type="primary" @click="handleSubmit('formItem')">提交</i-button>
                    <i-button type="ghost" style="margin-left: 8px">取消</i-button>
                </FormItem>
            </Row>
        </Form>
        </Col>
        <Col span="12">
        <div v-for="item in formItem.img_list" style="text-align:center;">
            <img style="width:414px" :src="item.url" />
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
                style_list: [],
                //style_list_price: [],
                img_list: [],
                stock:1000,
                visits: 1000,
                publish_date:"",
                is_online: "1"
            },
            img_prefix:"",
            ruleValidate: {
                title: [{
                    required: true,
                    type: "string",
                    message: "请填写标题",
                    trigger: "change"
                }],
                style_list: [{
                    required: true,
                    type: 'array',
                    min: 1,
                    message: '最少填写一项',
                    trigger: 'change'
                }],
                publish_date: [{
                    required: true,
                    type: "date",
                    message: "请填写发布日期",
                    /**
                    trigger: "change",
                    validator:function(rule,value,callback)
                    {
                        if(value == "")
                        {
                            return callback(new Eerror("日期不能为空"))
                        }
                    }
                    **/
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
        this.GetAddImgPrefix();
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
            this.$store.dispatch("GetPurchaseAgentDetail", this.$route.params.id).then(detail => {
                /**
                    //解析图片的前缀信息
                    let s_index = detail.result.img.lastIndexOf('/');
                    var img_name = detail.result.img.substring(s_index+1);
                    this.img_prefix = img_name.substring(0,img_name.length - 4);

                    this.formItem.img_list.push({status:1,url:detail.result.img});
                    for (let i = 0; i < detail.result.desc.length; i++) {
                        var obj = {
                            status: 1,
                            url: detail.result.desc[i]
                        };
                        this.formItem.img_list.push(obj);
                    }
                    for (let j = 0; j < detail.result.item_list.length; j++) {
                        this.formItem.style_list.push(parseInt(detail.result.item_list[j].id));
                    }
                    for (let i = 0; i < this.initData.style_list.length; i++) {
                        var is_find = false;
                        var price = 0;
                        for (let j = 0; j < detail.result.item_list.length; j++) {
                            if (detail.result.item_list[j].id == this.initData.style_list[i].id) {
                                is_find = true;
                                price = detail.result.item_list[j].price;
                                break;
                            };
                        }
                        this.formItem.style_list_price.push(price);
                    }
                    **/
                    this.formItem.title = detail.result.title;
                    this.formItem.is_online = detail.result.is_online;
                    this.formItem.visits = detail.result.visits;
                    this.formItem.publish_date = detail.result.start_time;
                    var arr = detail.result.style_list.split("|");
                    arr.forEach(element => {
                        var tmp = element.split("#");
                        var obj = {name:tmp[0],price:tmp[1]};
                        this.formItem.style_list.push(obj);
                    });
                    this.formItem.img_list.push({url:detail.result.main_img});
                    this.formItem.img_list.push({url:detail.result.bg_img_list});
                   // console.log(this.formItem,detail,"fomr_item");
                });
        },
        handleSubmit(name) {
            this.$refs[name].validate(valid => {
                if (!valid) {
                    this.$Message.error("表单验证失败!");
                    return false;
                }
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
        },
        GetAddImgPrefix()
        {
            this.loading = true;
            this.$store.dispatch("GetAddImgPrefix",{"id":this.$route.params.id,"cid":this.$route.params.cid}).then(rsp => {
                    if(rsp.code == 0)
                    {
                        this.img_prefix = rsp.result.prefix_name;
                    }
                })
                .catch(err => {

                });
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
