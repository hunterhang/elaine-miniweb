<template>
    <div>
        <!--
            <PhotoList v-model="list"></PhotoList>
            -->
        <Scroll :on-refresh="onRefresh" :is_more = "is_more">
            <div class="show_box">
                    <ProductItem v-for="(item,index) in listdata" :key="index" :category_id="2" :title="item.title" :img="item.img" :product_id="item.id" :visits="item.visits"></ProductItem>
            </div>
        </Scroll>
    </div>
</template>
<script>
export default {
    name: 'customize',
    data() {
        return {
            num: 8, // 一次显示多少条
            pageStart: 0, // 开始页数
            pageEnd: 0, // 结束页数
            listdata: [], // 下拉更新数据存放数组
            is_more:1
        }
    },
    mounted: function () {
        this.getDressMatchList();
    },
    methods: {
        getDressMatchList() {
            let self = this;
            this.$http.get('http://www.welaine.com/Home/index/getDressMatchList',
                {
                    params: {
                        start: self.pageStart,
                        limit: self.num
                    }
                }).then(function (response) {
                    var result = response.data;
                    console.log(result);
                    if (result.ret == 0) {
                        for (var i in result.data) {
                            var obj = {};
                            obj.id = result.data[i].id;
                            obj.title = result.data[i].title;
                            obj.img = result.data[i].img;
                            obj.visits = result.data[i].visits;
                            self.listdata.push(obj);
                        }
                        self.is_more = result.is_more;
                        self.pageStart = self.pageStart + result.data.length;
                    }
                }).catch(function (error) {
                    console.log(error);
                });
        },
        onRefresh() {
            this.getDressMatchList();
        }
    }
}

</script>
<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
.show_box {
    display: flex;
    flex-direction: row;
    justify-content: space-between;
    flex-wrap: wrap;
}

.show_box div {
    width: 48%;
    padding-bottom: 10px;
}

.show_box img {
    width: 100%;
}

.item_title {
    width: 100%;
    text-align: center;
}
</style>
