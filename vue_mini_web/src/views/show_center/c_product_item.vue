<template>
    <div class="box_list_item">
        <!--
        <div v-if="category_id==3"><router-link :to="{ name: 'customize', query: { id: product_id }}"><img :src="imgg" @load="load_img"></router-link></div>
        -->
        <div><img :src="imgg" @load="load_img" @click="jump(category_id,product_id)"></div>
        <div id="text">    
            <div style="width:80%;text-align:left;">{{title}}</div>
            <div ><img src="static/Eye.svg"></div>
            <div style="margin-left: 5px;">{{visits}}</div>
        </div>
        <!---->
    </div>
</template>
<script>
export default {
    data() {
        return {
            imgg:"static/default_product_img.jpg"
        }
    },
    props:{
        product_id:{
            tyupe:Number,
            required:true
        },
        category_id:{
            type:Number,
            required:true
        },
        title:{
            type:String,
            required:true
        },
        img:{
            type:String,
            required:true
        },
        visits:{
            type:String,
            required:true
        },
        order_num:{
            type:String,
            required:false
        }
    },
    mounted: function () {
        
    },
    watch: {
        img(val)
        {
            console.log("aaa",val);
            this.imgg = val;
        }
    },
    methods: {
        load_img:function(){
            console.log("load_img");
            //this.imgg = "/dist/static/default_product_img.jpg";
            this.imgg = this.img;
        },
        jump:function(category_id,product_id){
            var openid = this.$cookie.get("openid");
            let self = this;
            this.$http.get('/Home/index/reportVisit',
                {
                    params: {
                        id: product_id,
                        openid: openid,
                        category_id:category_id 
                    }
                }
            ).then(function(response) {
                if (response.data.ret == 0) {
                    if(category_id == 2)
                    {
                        location.href = self.img;
                        //self.$router.go({path:self.img});
                        //self.$router.go({path: self.img});
                    }else{
                        self.$router.push({name: 'customize',query:{id:self.product_id}});
                    }
                    /**
                    if(category_id == 1)
                    {
                        //this.$router.push({name: arr[0],query:{id:arr[1]}});
                        location.href = "/dist/user_design_pay.html?t=123#/designer?id=" + vm.product_id;
                    }
                    if(category_id == 2)
                    {
                        location.href = vm.img;
                    }
                    if(category_id == 3)
                    {
                        location.href = "http://www.welaine.com/dist/index.html?t=123#/customize?id=" + vm.product_id;
                    }
                    **/
                }
            }).catch(function(error) {
                console.log(error);
            })
        }
    }
}
</script>
<style scoped>

.box_list_item {
    width: 48%;
    padding-bottom: 10px;
}
.box_list_item img {
    width: 100%;
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
