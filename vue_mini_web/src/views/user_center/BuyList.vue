<template>
  <div>
      <ItemList v-bind:list="list" :type=2 ></ItemList>
  </div>
</template>
<script>
import ItemList from '@/views/user_center/ItemList'
export default {
  data() {
    return {
        list:[]
    }
  },
  mounted: function() {
      this.getList();
  },
  methods: {
      getList:function(){
          var openid = this.$cookie.get("openid");
          let self = this;
          var obj = {openid:openid,status:1}
          this.$store.dispatch('GetOrderList', obj).then(rsp => {
              if (rsp.ret == 0) {
                  var data = rsp.data;
                  for (var i in data) {
                      var obj = {};
                      obj.img = data[i].product_img;
                      obj.id = data[i].order_id;
                      obj.show_type = data[i].is_dress_model;
                      self.list.push(obj);
                  }
              }
          }).catch(function (error) {
              console.log(error);
          });
        
      }

  },
    components:{ItemList}
}

</script>
<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>

</style>
