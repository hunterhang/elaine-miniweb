<template>
    <div class="animated fadeIn">
        <Row>
            <Col :sm="24" :md="24" :lg="24">
            <Form ref="form" :label-width="60" :rules="ruleValidate" inline>
                <Form-item label="类型" style="width:10%;" prop="cid">
                        <Select v-model="form.cid" placeholder="请选择">
                            <Option value="1">设计师</Option>
                            <Option value="3">品牌买手</Option>
                        </Select>
                    </Form-item>
                <Form-item label="开始时间" prop="b_date"  style="width:10%;">
                    <Date-picker type="date" placeholder="选择日期" format="yyyy-MM-dd" v-model="form.b_date" @on-change="form.b_date=$event"></Date-picker>
                </Form-item>

                <Form-item label="结束时间" prop="e_date"  style="width:10%;">
                    <Date-picker type="date" placeholder="选择日期" format="yyyy-MM-dd" v-model="form.e_date" @on-change="form.e_date=$event"></Date-picker>
                </Form-item>
                <Form-item label="是否上线" style="width:15%;" prop="is_online">
                    <Select v-model="form.is_online" placeholder="请选择">
                        <Option value="0">未上线</Option>
                        <Option value="1">已上线</Option>
                    </Select>
                </Form-item>
                <Form-item label="标题" style="width:20%;" prop="title">
                    <Input v-model="form.title" placeholder="请输入" ref="title"></Input>
                </Form-item>
                <Button type="primary" icon="ios-search" @click="onSearch('form',1)">搜索</Button>
                <Button type="primary" @click="handleAdd(1)">新增设计师作品</Button>
                <Button type="primary" @click="handleAdd(3)">新增品牌买手作品</Button>
            </Form>
            </Col>
        </Row>
        <div style="position:relative;">
            <Table :columns="table_column" :data="table_data" ref="table"></Table>
            <div style="position:absolute;top:0px;width:100%;height:100%;display: flex;
                            align-items: center;
                            justify-content: center;background: rgba(210, 216, 222, 0.5);" v-if="list_loadding">
                <Spin size="large"></Spin>
                <h6 style="color:#2d8cf0;margin-top:10px;">正在获取数据...</h6>
            </div>
        </div>
        <Page :total="this.page_total_length" show-total @on-change="setInitPage" style="text-align:right;margin-top:50px"></Page>
    </div>
    </template>

<script>
export default {
    data() {
        return {
            form: {
                is_online: "",
                b_date: "",
                e_date: "",
                title: "",
                cid:0,
                order_num:0,
                stock:1000
            },
            ruleValidate: {
           },
            //table page
            list_loadding: false,
            page_total_length: 0,
            table_data: [],
            total_table_data:[],
            page_begin: 1,
            table_column: [{
                    title: "标题",
                    key: "title",
                    ellipsis: "true"
                },
                {
                    title: "图片",
                    key: "main_img",
                    ellipsis: "true",
                    render: (h, params) => {
                        return h("img", {
                            domProps: {
                                src: params.row.main_img,
                                height: "80"
                            }
                        });
                    }
                },
                {
                    title: "类型",
                    key: "cid",
                    ellipsis: "true",
                    render: (h, params) => {
                        var name = "品牌代卖"
                        if(params.row.cid =="1"){
                            name = "设计师";
                        };
                        return name;
                    }
                },
                {
                    title: "款式",
                    key: "style_list",
                    ellipsis: "true",
                    render: (h, params) => {
                        var list = [];
                        var list1 = params.row.style_list.split("|");
                        for (let i = 0; i < list1.length; i++) {
                            var str = list1[i];
                            var arr = str.split("#");
                            var name = arr[0];
                            var obj = h(
                                "Button", {
                                    props: {
                                        type: "primary",
                                        size: "small"
                                    },
                                    style: {
                                        marginBottom: "3px",
                                        marginRight:"3px"
                                    }
                                },
                                name
                            );
                            list.push(obj);
                        }
                        //var obj = h("div", list);
                        //console.log(obj,"xxxx");
                        return h("div",{style:"white-space: pre-wrap;"},list);
                    }
                },
                {
                    title: "订单数",
                    key: "order_num",
                    ellipsis: "true",
                    render: (h, params) => {
                        return "0";
                    }
                },
                {
                    title: "库存",
                    key: "stock",
                    ellipsis: "true"
                },
                {
                    title: "是否上线",
                    key: "is_online",
                    ellipsis: "true",
                    render: (h, params) => {
                        if (params.row.is_online == 1) {
                            return "已上线";
                        } else {
                            return "未上线";
                        }
                    }
                },
                {
                    title: "上线时间",
                    key: "start_time",
                    ellipsis: "true"
                },
                {
                    title: "被访问数",
                    key: "visits",
                    ellipsis: "true"
                },
                {
                    title: "创建时间",
                    key: "created",
                    ellipsis: "true"
                },
                {
                    title: "操作",
                    key: "action",
                    align: "center",
                    ellipsis: "true",
                    render: (h, params) => {
                        return h("div", [
                            h(
                                "Button", {
                                    props: {
                                        type: "primary",
                                        size: "small"
                                    },
                                    on: {
                                        click: () => {
                                            this.$router.push({
                                                path: "/purchase_agent_edit/" +
                                                    params.row.id+"/"+params.row.cid
                                            });
                                        }
                                    },
                                    style: {
                                        marginRight: "5px"
                                    }
                                },
                                "编辑"
                            ),
                            h(
                                "Button", {
                                    props: {
                                        type: "error",
                                        size: "small"
                                    },
                                    on: {
                                        click: () => {
                                            this.remove_confirm(params.row.id);
                                        }
                                    }
                                },
                                "删除"
                            )
                        ]);
                    }
                }
            ]
        };
    }, //data
    methods: {
        remove_confirm(index) {
            this.$Modal.confirm({
                title: "警告",
                content: "<p>确认要删除这条数据吗？</p>",
                onOk: () => {
                    this.delete_row(index);
                }
            });
        },
        delete_row(index) {
            this.loading = true;
            this.$store
                .dispatch("DeletePurchaseAgentRow", index)
                .then(rsp => {
                    this.loading = false;
                    if (rsp.code == 0) {
                        this.$Message.info("删除成功");
                        this.setInitPage(begin);
                    } else {
                        this.$Message.error("删除数据异常");
                    }
                })
                .catch(err => {
                    //                this.$Message.error("删除数据异常");
                });
        },
        detail(index) {
        },
        setInitPage(page_num) {
           // console.log(begin,"xxx");
            this.loading = true;
            this.$store
                .dispatch("GetPurchaseAgentList", this.form)
                .then(rsp => {
                    this.loading = false;
                    if (rsp.code == 0) {
                        this.total_table_data = rsp.result.list;
                        /**
                        this.page_begin = begin;
                        let list = rsp.result.list;
                        this.page_total_length = list.length;
                        this.total_table_data = [];
                        for ( let i = (begin - 1) * 10; i < (begin - 1) * 10 + 10; i++) {
                            if (i < list.length) {
                                this.total_table_data.push(list[i]);
                            }
                        }
                        **/
                    }
                    this.onSearch('form',page_num);
                })
                .catch(err => {
                    console.log("err", err);
                    this.loading = false;
                });
        },
        onSearch(name,page_num) {
            let b_date = this.form.b_date;
            let e_date = this.form.e_date;
            let is_online = this.form.is_online;
            let title = this.form.title;
            let cid = this.form.cid;
            var search_data = [];
            this.page_total_length = 0;
            for(var i = 0;i<this.total_table_data.length;i++)
            {
                var row = this.total_table_data[i];
                if(b_date != "")
                {
                    if(row['start_time'] < b_date+" 00:00:00")
                    {
                        continue;
                    }
                }
                if(e_date != "")
                {
                    if(row['start_time'] > e_date+ " 23:59:59")
                    {
                        continue;
                    }
                }
                if(is_online === "0" || is_online === "1")
                {
                    if(row["is_online"] != is_online)
                    {
                        continue;
                    }
                }
                if(title != "")
                {
                    if(row["title"].indexOf(title) == -1)
                    {
                        continue;
                    }
                }
                if(cid != 0)
                {
                    if(row["cid"] != cid)
                    {
                        continue;
                    }
                }

                this.page_total_length++;
                if(this.page_total_length< (page_num -1)*10 || this.page_total_length > page_num*10)
                {
                    continue;
                }
                search_data.push(row);
            }
            this.table_data = search_data;
            //this.page_total_length = search_data.length;
       },
        handleAdd(cid) {
            this.$router.push({
                path: "/purchase_agent_edit/0/"+cid
            });
        },
        handleRemove(index) {
            this.formDynamic.items.splice(index, 1);
        }
    },
    mounted() {
        const vue = this;
        this.list_loadding = true;
        setTimeout(function () {
            vue.list_loadding = false;
        }, 500);
        this.setInitPage(1);
    }
};
</script>
