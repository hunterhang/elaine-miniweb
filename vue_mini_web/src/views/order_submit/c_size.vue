<template>
    <div>
        <div class="item_title margin_top2">Size</div>
        <div class="display_flex margin_top1">
            <div class="corner size_item">{{size_name}}</div>
        </div>
    </div>
</template>
<script>

export default {
    data() {
        return {
            size_name:"",

        }
    },
    props:{
        selected_size:{
            type:Number,
            required:true
        }
    },
    watch:{
        selected_size: function(val)
        {
            this.getSizeList();
        }
    },
    mounted: function () {
        this.getSizeList();
    },
    methods: {
        getSizeList() {
            let vm = this;
            this.$http.get('http://www.welaine.com/Home/index/getSizeList', {
            }).then(function(response){
                if(response.data.ret == 0)
                {
                    var data = response.data.data;
                    for(var id in data.size_list)
                    {
                        if(vm.selected_size == id)
                        {
                            vm.size_name = data.size_list[id];
                            break;
                        }
                    }
                }
            }).catch(function(error){
                console.log(error);
            });
        }
    }
}
</script>
<style scoped>
.size_item {
    min-width: 30px;
    padding: 2px 5px;
}
</style>
