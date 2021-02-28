<<template>
    <div>
        <div class="margin_top1" style="text-align: left;">
            <div style="font-weight: bold;">寄衣地址</div>
            <div>
                <input v-if="is_disable" v-model="addr" placeholder="请输入寄衣地址" type="input" required="required" class="corner_input" disabled>
                <input v-else v-model="addr" placeholder="请输入寄衣地址" type="input" required="required" class="corner_input">
            </div>
        </div>
        <div class="margin_top1" style="text-align: left;">
            <div style="font-weight: bold;">手机号码</div>
            <div>
                <input v-if="is_disable" v-model="phone" placeholder="请输入手机号码" type="input" required="required" class="corner_input" disabled>
                <input v-else v-model="phone" placeholder="请输入手机号码" type="input" required="required" class="corner_input">
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
            }
        },
        props: {
            openid: {
                type: String,
                required: true
            },
            set_user_phone: {
                type: Function,
                required: true
            },
            set_user_addr: {
                type: Function,
                required: true
            },
            is_need_load:{
                type:Boolean,
                default: true,
                required:false
            },
            phone:{
                type: String,
                required: true
            },
            addr:{
                type: String,
                required: true
            },
            is_disable:{
                type:Boolean,
                default:false,
                required:false
            }
        },
        watch: {
            phone: function(val){
                this.set_user_phone(val);
            },
            addr: function(val)
            {
                this.set_user_addr(val);
            }

        },
        
        mounted:function(){
            if(this.is_need_load == true){
                this.getUserHistoryInfo(this.openid);
            }    
        },
        
        methods: {
            getUserHistoryInfo: function(openid) {
                let vm = this;
                this.$http.get('http://www.welaine.com/Order/index/getHistoryOrderInfo', {
                    params: {
                        openid: openid
                    }
                }).then((response) => {
                    if (response.data.ret == 0) {
                        let data = response.data.data;
                        //vm.addr = data.address;
                        //vm.phone = data.phone;
                        vm.set_user_addr(data.address);
                        vm.set_user_phone(data.phone);
                    }
                }, (response) => {
                    console.log('error');
                });
            }
        }
    }
</script>

<style scoped>

</style>

