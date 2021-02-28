<template>
    <div>
        <div v-for="(item,index) in list">
            <div class="corner_div order_list">
                <div style="width:35%;" @click="jump(item.id)">
                    <img v-bind:src="item.img">
                </div>
                <div style="width:45%;" class="order_father" @click="jump(item.id)">
                    <div class="order_desc">
                        <div v-if="1 == type">
                            <ul v-if="item.show_type == 1" slot="line_1">量身定制</ul>
                            <ul v-if="item.show_type == 2" slot="line_1">设计师定制</ul>
                            <ul slot="line_2" style="color:gray;">未支付,点击查看详情</ul>
                            <ul slot="line_3" style="color:gray;">您付款后我们将安排上门量身</ul>
                        </div>
                        <div v-if="2 == type">
                            <ul v-if="item.show_type == 1" slot="line_1">量身定制</ul>
                            <ul v-if="item.show_type == 2" slot="line_1">设计师定制</ul>
                            <ul slot="line_2" style="color:gray;">已支付,点击查看详情</ul>
                            <ul slot="line_3" style="color:gray;">我们将尽快安排上门量身</ul>
                        </div>
                    </div>
                </div>
                <div v-if="1 == type" class="delete" style="width:20%;position:relative;">
                    <div>
                        <img src="static/x.jpg" @click="show_dialog(item.id)">
                    </div>
                </div>
            </div>
        </div>
        <Dialog v-model="isShow" v-on:confirm="confirm_del" v-bind:data="data" title="弹窗标题">
            {{alert_content}}
        </Dialog>
    </div>
</template>
<script>
    export default {
        data() {
            return {
                //弹窗
                //type: true,
                isShow: false,
                alert_content: "未填写内容",
                data: ''
            }
        },
        props: {
            list: {
                type: Array,
                default: [],
                require: true
            },
            type: {
                type: Number,
                default: true
            }
        },
        mounted: function () {},
        methods: {
            show_dialog: function (id) {
                this.data = id;
                this.isShow = true;
                this.showAlert = true;
                this.alert_content = "确认删除订单？";
            },
            confirm_del: function (data) {
                var openid = this.$cookie.get("openid");
                let delete_id = this.data;
                let self = this;

                var obj = {openid:openid,order_id:delete_id}
                this.$store.dispatch('DelOrder', obj).then(rsp => {
                    if (rsp.ret == 0) {
                        for (var i = 0; i < self.list.length; i++) {
                            if (self.list[i].id == delete_id) {
                                self.list.splice(i, 1);
                                break;
                            }
                        }
                    }
                }).catch(function (error) {
                    console.log(error);
                });
            },
            jump: function (order_id) {
                //location.href = "/dist/order_submit.html?t=1&order_id="+order_id;
                this.$router.push({
                    path: 'order_submit',
                    query: {
                        order_id: order_id
                    }
                })
            }
        }
    }
</script>
<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
    .order_list {
        height: 130px;
        display: flex;
    }

    .order_list img {
        margin: 20px 5%;
        width: 90%;
        height: 90px;
    }

    .order_father {
        position: relative;
    }

    .order_desc {
        text-align: left;
        position: absolute;
        bottom: 20px;
    }

    .delete {
        position: relative;
    }

    .delete img {
        position: absolute;
        top: 0px;
        right: 0px;
        height: 20px;
        width: 20px;
        margin: 5px;
    }
</style>