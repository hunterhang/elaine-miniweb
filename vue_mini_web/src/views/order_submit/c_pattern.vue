<template>
    <div>
        <div class="item_title margin_top1">Pattern</div>
        <div class="display_flex margin_top1">
            <div class="corner patt_item" v-for="item in list">
                {{item.name}} {{item.price|price}}
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                list: []
            }
        },
        props: {
            selected_patt: {
                type: Array,
                required: true
            },
            type: {
                type: Number,
                required: true
            },
            patt_arr: {
                type: Array,
                required: true
            },
            callback: {
                type: Function,
                required: true
            }
        },
        watch: {
            patt_arr: function(val){
                this.patt_arr = val;
                this.set_list();
            }
        },
        methods:{
            set_list: function()
            {
                var price = 0;
                var ss = [];
                for(var index in this.selected_patt)
                {
                    var val = this.selected_patt[index];
                    var str = this.patt_arr[val];
                    var temp_arr = str.split("#");
                    var obj = {name:temp_arr[0],price:temp_arr[1]};
                    ss.push(obj);
                    price += parseInt(temp_arr[1]);
                }
                this.callback(price);
                this.list = ss;
                return ;
                for(var item in this.patt_arr)
                {
                    for(var item2 in this.selected_patt)
                    {
                        if(this.patt_arr[item].id == this.selected_patt[item2])
                        {
                            
                            ss.push(this.patt_arr[item]);
                            price += parseInt(this.patt_arr[item].price);
                        }
                    }
                }
                this.callback(price);
                this.list = ss;
            }
        },
        mounted: function() {
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

