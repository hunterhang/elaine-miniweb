<template>
  <div>
      <div class="page_header">
        会员中心
      </div>
      <UserHead></UserHead>
      <div class="gray_line body_width"></div>
      <div id="index_main">
          <div id="index_tab_list">
              <router-link to="buy_list" tag="div" active-class="black">{{tab1_name}}</router-link>
              <router-link to="pay_list" tag="div" active-class="black">{{tab2_name}}</router-link>
              <div @click="call">{{tab3_name}}</div>
          </div>
          <div id="router_view">
              <router-view></router-view>
          </div>
      </div>  
    </div>
</template>

<script>
import UserHead from '@/components/UserHead'
export default {
    data () {
    return {
      tab1_name: '订单',
      tab2_name: '待支付',
      tab3_name: '售后'
    }
  },
  methods:{
    call: function()
    {
        window.location.href="tel:18665868692";
    }
  },
  components:{UserHead}
}
</script>

<style>
#index_main {
    width:87.4%;
    margin:0 auto;
    text-align:center;
}
#index_tab_list {
  margin-top:4%;
  display: flex;
  flex-direction: row;
  justify-content: space-between;
}
#index_tab_list div{
    width:30%;
    text-align: center;
    text-decoration: none;
    line-height:32px;
    font-weight:700;
    border-radius:7px;
    border: 1px solid #B2B2B2;
}
#router_view{
  margin-top:4%;
}
.corner{
      border-radius: 4px;
      border: 1px solid #B2B2B2;
      padding:2px 5px;
      text-align:center;
}
.page_header{

  text-align:center;border-bottom:1px solid #cccccc;line-height:49px;font-size:13px;
}


</style>
