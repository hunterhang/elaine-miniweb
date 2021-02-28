<template>
  <div>
      <PhotoList v-model="list"></PhotoList>
  </div>
</template>

<script>
  import PhotoList from '@/components/PhotoListTpl'
  export default {
  name: 'customize',
  data () {
    return {
      list: [],
    }
  },
  mounted: function(){
      this.getDesignerIndex();
  },
  methods:{
      getDesignerIndex()
      {
          this.list = [
            {url:"http://www.welaine.com/ui/dress/VOL0014_01.png",type:1},
            {url:"http://www.welaine.com/ui/dress/VOL0014_02.png",type:1},
            {url:"http://www.welaine.com/ui/dress/VOL0014_03.png",type:1},
            {url:"https://www.baidu.com/img/bd_logo1.png",type:2,poster:"http://vjs.zencdn.net/v/oceans.png"}
          ];
      }
  },
  components:{PhotoList}
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>

</style>
