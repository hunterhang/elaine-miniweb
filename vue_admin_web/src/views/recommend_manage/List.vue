<template>
    <div class="animated fadeIn">
        <Row>
            <Col :sm="24" :md="24" :lg="24">
            <Form ref="form" :label-width="60" :rules="ruleValidate" inline>
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
                <Button type="primary" @click="handleAdd(3)">新增</Button>
                <!--<Button type="primary" @click="handleAdd()">新增</Button>-->
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
                cid:0
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
                    title: "图一",
                    key: "img1",
                    ellipsis: "true",
                    render: (h, params) => {
                        return h("img", {
                            domProps: {
                                src: params.row.img1,
                                height: "80"
                            },
                            on: {
                                click:() =>{this.jump_good(params.row.link1)}
                            }
                        });
                    }
                },
                {
                    title: "图二",
                    key: "img2",
                    ellipsis: "true",
                    render: (h, params) => {
                        var str = params.row.img2;
                        var arr = params.row.img2.split("|");
                        var list = [];
                        for (let i = 0; i < arr.length; i++) {
                            var str = arr[i];
                            var obj = h(
                                "img",{
                                    domProps: {
                                        src: str,
                                        height: "80"
                                    }
                                }
                            );
                            list.push(obj);
                        }
                        return h("div",{style:"white-space: pre-wrap;",on:{click:() =>{this.jump_good(params.row.link2)}}},list);
                    }
                },
                {
                    title: "图三",
                    key: "img3",
                    ellipsis: "true",
                    render: (h, params) => {
                        var str = params.row.img3;
                        var arr = params.row.img3.split("|");
                        var list = [];
                        for (let i = 0; i < arr.length; i++) {
                            var str = arr[i];
                            var obj = h(
                                "img",{
                                    domProps: {
                                        src: str,
                                        height: "80"
                                    }
                                }
                            );
                            list.push(obj);
                        }
                        return h("div",{style:"white-space: pre-wrap;",on:{click:() =>{this.jump_good(params.row.link3)}}},list);
                    }
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
                    title: "创建时间",
                    key: "created",
                    ellipsis: "true"
                }
                ,
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
                                                path: "/recommend_manage_edit/" +params.row.id
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
        jump_good: function(id){
            window.open("http://www.welaine.com/dist/index.html#/customize?id="+id);
            //window.location.href="http://www.welaine.com/dist/index.html#/customize?id="+id;
            //console.log(id,"xxx");
            return ;
        },
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
            this.loading = true;
            this.$store
                .dispatch("GetRecommendList", this.form)
                .then(rsp => {
                    this.loading = false;
                    if (rsp.code == 0) {
                        this.total_table_data = rsp.result.list;
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
                path: "/recommend_manage_edit/0"
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
