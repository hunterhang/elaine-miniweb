<template>
  <div>
      <div class="title">
          elainewang 精选
      </div>
      <hr />
      <div id="index_main">
          <div id="index_tab_list">
              <router-link to="designer_list" tag="div" active-class="black">{{tab2_name}}</router-link>
              <router-link to="customize_list" tag="div" active-class="black">{{tab3_name}}</router-link>
              <router-link to="dress_match_list" tag="div" active-class="black">{{tab4_name}}</router-link>
          </div>
          <div id="router_view">
              <router-view></router-view>
          </div>
      </div>
  </div>
</template>

<script>
export default {
    data () {
    return {
      tab2_name: '设计师作品',
      tab3_name: '品牌买手',
      tab4_name: '每日穿搭',
      scan_img:"http://www.welaine.com/ui/images/icon_scan.png",
    }
  }
}
</script>

<style scoped>
.title{
  text-align: center;
  line-height: 40px;
}
#index_main {
    width:87.4%;
    margin:0 auto;
    text-align:center;
}
#index_tab_list {
  margin-top:32px;
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

.corner{
      border-radius: 4px;
      border: 1px solid #B2B2B2;
      padding:2px 5px;
      text-align:center;
}

</style>
