<template>
  <div class="photo_list">
      <div v-for="item in list">
          <img v-bind:src="item.url">
      </div>
  </div>
</template>

<script>
export default {
  name: 'customize',
  data () {
    return {
      list: [],
    }
  },
  props: {
    value:{
          type:Array,
          required: true,
          default:{}
        }
      },
  mounted: function(){
      //this.getDesignerIndex();
  },
   watch: {
        value(val){
            this.list = val;
        }
    },

  methods:{
    

  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
.photo_list{
    display: flex;
    flex-direction: row;
    flex-wrap: wrap;
    justify-content: space-between;
}
.photo_list div{
    width:48%;
    margin-top:4%;

}
.photo_list img{
  width:100%;
  height: 200px;
}
</style>
