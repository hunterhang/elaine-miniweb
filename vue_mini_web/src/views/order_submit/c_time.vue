<<template>
    <div>
        <div class="item_title margin_top2">预约时间</div>
        <div class="margin_top1" style="margin-top:10px;text-align:left;">
            <span class="corner patt_item" style="font-weight:500;font-family:'Microsoft YaHei';">{{name}}</span>
        </div>
    </div>
</template>
<script>
export default {
    data() {
        return {
            name:""
        }
    },
    props:{
        selected_time:{
            type:Number,
            required:true
        },
        selected_date:{
            type:Number,
            required:true
        }
    },
    watch:{
        selected_time: function(val)
        {
            this.selected_time = val;
        },
        selected_date: function(val)
        {
            this.selected_date = val;
        }
    },
    mounted: function () {
        this.getSizeList();
    },
    methods: {
        getSizeList() {
            let vm = this;
            this.$http.get('http://www.welaine.com/Home/index/getDateList', {
            }).then(function(response){
                var s = "";
                if(response.data.ret == 0)
                {
                    var data = response.data.data;
                    for(var id in data.date_list)
                    {
                        if(vm.selected_date == id)
                        {
                            s = data.date_list[id];
                            break;
                        }
                    }
                    s += " ";
                    for(var id in data.time_list)
                    {
                        if(id == vm.selected_time)
                        {
                            s += data.time_list[id];
                            break;
                        }
                    }
                    vm.name = s;
                }
            }).catch(function(error){
                console.log(error);
            });
        }
    }
}
</script>
<style scoped>
.patt_item {
    margin-right: 10px;
    min-width: 30px;
    padding: 2px 5px;
}
</style>

