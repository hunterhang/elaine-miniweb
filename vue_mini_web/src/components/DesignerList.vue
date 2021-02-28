<template>
  <div>
      <!--
      <PhotoList v-model="list"></PhotoList>
      -->
      <v-scroll :on-refresh="onRefresh" :on-infinite="onInfinite">
      <div class="show_box">
         <div v-for="(item,index) in listdata" >
            <img v-bind:src="item.img">
            <div class="item_title" style="width:100%;text-align:center;">{{item.title}}</div>
          </div>
         <div v-for="(item,index) in downdata" >
            <img v-bind:src="item.img">
            <div class="item_title" style="width:100%;text-align:center;">{{item.title}}</div>
         </div>
         </div>
    </v-scroll>
  </div>
</template>

<script>
  import Scroll from './Scroll'
  export default {
data () {
    return {
      counter : 1, //默认已经显示出15条数据 count等于一是让从16条开始加载
      num : 3,    // 一次显示多少条
      pageStart : 0, // 开始页数
      pageEnd : 0, // 结束页数
      listdata: [], // 下拉更新数据存放数组
      downdata: []  // 上拉更多的数据存放数组
    }
  },
  mounted : function(){
     this.getList();
  },
  methods: {
    getList(){
        let vm = this;
        this.$http.get('http://www.welaine.com/Home/index/getCustomizeList',{
          params:{
              start:this.pageStart,
              limit:this.num
          }
        }).then((response) => {
            var data = response.data.data;
            console.log(data);
            for(var id in data.list)
            {
                var obj = {};
                obj.id = data.list[id].id;
                obj.img = data.list[id].img1;
                obj.title = data.list[id].title;
                vm.listdata.push(obj);
            }
            console.log(data.list);
            this.pageStart = this.pageStart + data.list.length;
        }, (response) => {
                console.log('error');
        });
    },
    onRefresh(done) {
             this.getList();
              console.log("onrefresh");
              done() // call done
      
    },
    onInfinite(done) {
              let vm = this;
              console.log("onInfinite");
              /**
              vm.$http.get('https://api.github.com/repos/typecho-fans/plugins/contents/').then((response) => {
                  vm.counter++;
                  vm.pageEnd = vm.num * vm.counter;
                  vm.pageStart = vm.pageEnd - vm.num;
                  let arr = response.data;
                     let i = vm.pageStart;
                     let end = vm.pageEnd;
                     for(; i<end; i++){
                        let obj ={};
                        obj["name"] = arr[i].name;
                        vm.downdata.push(obj);
                         if((i + 1) >= response.data.length){
                          this.$el.querySelector('.load-more').style.display = 'none';
                          return;
                        }
                        }
                  done() // call done
                   }, (response) => {
                    console.log('error');
                });
                **/
           }
  },
  components : {
'v-scroll': Scroll
  }}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
.show_box{
  display: flex;
  flex-direction: row;
  justify-content: space-between;
  flex-wrap: wrap;
}
.show_box div{
    width: 48%;
    padding-bottom: 10px;
}
.show_box img{
  width:100%;
}
.item_title{
  width:100%;
  text-align: center;
}
</style>
