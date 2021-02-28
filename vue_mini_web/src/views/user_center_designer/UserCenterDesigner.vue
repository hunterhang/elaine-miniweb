<template>
    <div>
        <div class="page_header">
            我的设计
        </div>
        <UserHead></UserHead>
        <div id="index_main" class="body_width margin_top2">
            <div class="upload_button corner" @click="jump">上传我的设计</div>
            <div class="desc margin_top1">
                <div>不要埋没你的服装设计灵感</div>
                <div>上传您的设计，让大家定制的设计</div>
            </div>
        </div>
        <div class="box_list body_width">
            <div v-for="item in list" class="box_list_item">
                <div>
                    <img v-bind:src="item.img">
                </div>
                <div v-if="item.status == 1" id="text">
                    <!--{{item.title}}-->
                    <div><img src="/dist/static/Eye.svg"></div> <div style="margin-left:5px;">{{item.visits}}</div><div style="margin-left:20px;"><img src="/dist/static/Money.svg"></div> <div style="margin-left:5px;">{{item.income|price}}</div>
                </div>
                <div v-if="item.status == 0" id="text">
                    {{item.title}} (<font color="red">审核中</font>)
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import UserHead from '@/components/UserHead'
import { Cookie } from '@/components/comm.js'
export default {
    data() {
        return {
            list: []
        }
    },
    mounted: function () {

        this.getList();
    },
    methods: {
        getList: function () {
            let vm = this;
            this.$http.get(
                'http://www.welaine.com/Home/index/get_product_list',
                {
                    params: {
                        openid: Cookie.get("openid")
                    }
                }
            ).then((response) => {
                console.log(response.data);
                if (response.data.ret == 0) {

                    var data = response.data.data;
                    for (var i in data) {
                        var obj = {};
                        obj.id = data[i].id;
                        obj.img = data[i].img;
                        obj.title = data[i].title;
                        obj.status = data[i].status;
                        obj.income = data[i].income;
                        obj.visits = data[i].visits;
                        vm.list.push(obj);
                    }
                }
            }, (response) => {
                console.log('error');
            });
        },
        jump: function () {
            location.href = "/dist/user_upload_product.html#"
        }
    },
    components: { UserHead }
}

</script>
<style scoped>
#index_main {
    margin: 20px auto;
    text-align: center;
}

.upload_button {
    width: 100%;
    height: 40px;
    margin: auto;
    line-height: 40px;
}

.desc {
    text-align: center;
}
.desc div{
    line-height: 25px;
}

.box_list {
    display: flex;
    flex-direction: row;
    justify-content: space-between;
    flex-wrap: wrap;
}

.box_list_item {
    width: 48%;
    padding-bottom: 10px;
}

#text {
    text-align: center;
    display: flex;
}
#text img{
    height: 15px;
}
#text div{
    line-height: 15px;
}
</style>
