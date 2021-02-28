<template>
  <div>
      <div class="corner_user_head" >
            <!--<img src="ui/images/camera_v2.png" style="margin:38px auto;"></img>-->
          <img v-bind:src="user_img">
      </div>
      <div class="user_name">{{user_name}}</div>
      <div class="vip_icon">
            <img src="static/vip.png">
      </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
        user_img: "",
        user_name:""
    }
  },
  mounted: function(){
      console.log("mounted");
      this.getUserInfo();
  },
  methods: {
      getUserInfo: function(){
        this.user_img = decodeURIComponent(this.$cookie.get("user_head"));
        this.user_name = this.$cookie.get("nickname");
      }
  }
}
</script>
<style scoped>
.corner_user_head {
  width:100px;height:100px;border-radius:50px;border:solid #f9f9f9 1px;background-color:#f9f9f9;text-align:center;margin:23px auto;
}
.corner_user_head img {
  width:100px;
  border-radius:50px;
}
.vip_icon{
  text-align:center;
  margin:15px auto;  
}
.vip_icon img{
  width: 20px;
}
.user_name{
  text-align:center;
}
</style>
