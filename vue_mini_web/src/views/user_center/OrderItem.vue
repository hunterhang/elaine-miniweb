<template>

</template>
<script>
export default {
  name: 'buy_item',
  data() {
  },
  props:{
      obj:{
        type:Object,
        require:true
      }
  },
  mounted: function() {
  },
  methods: {
      gotoFatherDel: function(id)
      {
         this.$emit('del', id);
         return ;
          //todo:
          //this.$http.get('http://www.welaine.com/Order/index/del',)
      }
  }
}

</script>
<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
.order_list {
    height: 130px;
    display: flex;
}
.order_list img{
  margin:20px 5%;width:90%;height:90px;
}
.order_father
{
  position: relative;
}
.order_desc{
  text-align: left;
  position: absolute;
  bottom:20px;
}
.delete{
  position: relative;
}
.delete img{
    position: absolute; 
    top: 0px;
    right:0px;
    height:20px;
    width:20px;
    margin:5px;
}

</style>
