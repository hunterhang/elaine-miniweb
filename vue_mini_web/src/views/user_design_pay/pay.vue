<template>
  <div>
      <div class="corner_div" style="height:auto;">
          <div style="margin:10px;">
              <img id="dress_img" v-bind:src="main_img|real_img" style="width:100%;">
          </div>
      </div>
      <div id="size_item" class="margin_top">
          <div class="english_title_font"> 
              size
          </div>
          <div id="size_list">
              <div v-for="item in size_list" class="corner" v-bind:class="{'black':calc_data.size_val == item.id}" 
                v-bind:id="item.id" @click="click_size(item)">{{item.name}}</div>  
          </div>
      </div>
      <div id="date_item" class="margin_top" v-if="calc_data.size_val == show_date_id">
          <div>
              预约时间
          </div>
          <div id="date_list">
              <div v-for="item in date" class="corner" v-bind:class="{'black':calc_data.date_val == item.id}" v-bind:id="item.id" @click="click_date(item)">{{item.name}}</div>
          </div>
          <div id="time_list">
              <div v-for="item in time" class="corner" v-bind:class="{'black':calc_data.time_val == item.id}" v-bind:id="item.id" @click="click_time(item)">{{item.name}}</div>
          </div>
      </div>
      <div id="patt_item" class="margin_top">
          <div>Pattern</div>
          <div id="patt_list">
              <div v-for="item in patt_list" class="corner" v-bind:class="{'black':is_selected_patt(item.id)}"  v-bind:id="item.id" v-bind:price="item.price" 
          @click="click_patt(item)">{{item.name}} ¥{{item.price|price}}</div>
          </div>
      </div>
      <div id="total_price_item" class="margin_top">
          <div>Total: ¥{{total_price|price}}元</div>
      </div>
      <div class="margin_top">
        <div class="corner_pay" id="pay_v2" @click="submit_form">
            立即支付
        </div>
      </div>
      <div class="gray_line" style="margin-top:50px;"></div>
      <div>
          <img v-for="item in body_img" class="body_img" v-bind:src="item"></img>  
      </div>
      <Dialog v-model="isShow" v-bind:type="type" title="弹窗标题" >
          {{alert_content}}
      </Dialog>
  </div>
</template>

<script>
import Dialog from '@/components/WeuiDialog.vue'
import {Auth,Cookie} from '@/components/comm.js'
export default {
    name: 'customize',
    data () {
        return {
            //url get param
            id:0,
            show_type:2,
            //展示内容
            main_img:"",
            body_img:[],
            size_list:[],
            patt_list:[],
            date:[],
            time:[],
            show_date_id:5, 
            customize_id:0,
            calc_data:{
                size_val:1,
                date_val:1,
                time_val:1,
                patt_val:[],
                total_price:0
            },
            //弹窗
            type:false,
            isShow:false,
            alert_content:"未填写内容"
        }
    },
    mounted: function(){
      this.id = Auth.get("id");
      var openid = Cookie.get("openid");
      //this.show_type = Auth.get("show_type");
      this.getUserProductInfo(this.id);
     
      this.getDateList();
      this.getSizeList();
    },
    methods:{
      getDateList()
      {
          this.$http.get('http://www.welaine.com/Home/index/getDateList').then((response) => {
            console.log(response.data);
              var data = response.data.data;
              for(var id in data.date_list)
              {
                  var obj = {};
                  obj.id = id;
                  obj.name = data.date_list[id];
                  this.date.push(obj);
              }
              for(var id in data.time_list)
              {
                var obj = {};
                obj.id = id;
                obj.name = data.time_list[id];
                this.time.push(obj);
              }
          }, (response) => {
                  console.log('error');
          });
      },
      getSizeList(){
          this.$http.get('http://www.welaine.com/Home/index/getSizeList').then((response) => {
                var data = response.data.data;
                for(var id in data.size_list)
                {
                    var obj = {};
                    obj.id = id;
                    obj.name = data.size_list[id];
                    this.size_list.push(obj);
                }
            }, (response) => {
                    console.log('error');
            });
      },
      getUserProductInfo: function(id){
          let vm = this;
          this.$http.get('http://www.welaine.com/Home/index/getUserProductInfo',
            {
              params: {
                id:vm.id
            }
          }).then(function(response){
              console.log(response.data);
              if(response.data.ret == 0)
              {
                  vm.main_img = response.data.data.img;
                  vm.patt_list = response.data.patt;
                  vm.id = response.data.data.id;
              }
          }).catch(function(error){
                console.log(error);
          });
      },
      getUserProductSize: function()
      {
        let vm = this;
          this.$http.get('http://www.welaine.com/Home/index/getUserProductSize').then(function(response){
            vm.main_img = response.data.data.index_img;
            vm.body_img = response.data.data.body_img_list;
            vm.patt_list = response.data.data.parts_arr;
            vm.customize_id = response.data.data.id;
          }).catch(function(error){
                console.log(error);
      })},
      click_size:function(obj){
          this.calc_data.size_val = obj.id;
      },
      click_date:function(obj){
          this.calc_data.date_val = obj.id;
      },
      click_time:function(obj){
          this.calc_data.time_val = obj.id;
      },
      click_patt:function(obj){
          if(this.calc_data.patt_val.indexOf(obj.id) === -1)
          {
              this.calc_data.patt_val.push(obj.id);
          }else{
              this.calc_data.patt_val = this.calc_data.patt_val.filter(item => item !== obj.id);
          }
      },
      is_selected_patt:function(id)
      {
          if(this.calc_data.patt_val.indexOf(id) == -1)
          {
            return false;
          }
          return true;
      },
      submit_form:function()
      {
          //检查参数
          if(this.calc_data.patt_val.length == 0)
          {
              this.alert_content = "请选择Pattern";
              this.isShow = true;
              return ;
          }
          
          var patt_str = "";
          for(var i in this.calc_data.patt_val)
          {
              patt_str += this.calc_data.patt_val[i]+",";
          }
          patt_str = patt_str.substring(0,patt_str.length -1);
          //var jump = "/dist/order_submit.html#/?id=3&size=2&pattern=2,3&date=1&time=1"
          var jump = "/dist/order_submit.html?timestamp=1.1&id="+this.id+"&size="+this.calc_data.size_val+"&pattern="+patt_str+"&date="+this.calc_data.date_val+"&time="+this.calc_data.time_val+"&show_type="+this.show_type;
          console.log(jump);
          location.href = jump;
      }
    },
    computed:{
        total_price:{
            get: function(){
                var total_price = 0;
                var patt_val = this.calc_data.patt_val;
                this.patt_list.forEach(function(item){
                    if(patt_val.indexOf(item.id) != -1)
                    {
                        console.log(item.price);
                        total_price = parseInt(total_price) + parseInt(item.price);
                    }
                });
                this.total_price = total_price;
                return total_price;
            }
        }

    },
    components:{Dialog}
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
#size_item{
    margin-top:4%;
    text-align: left;
}
#size_list{
    display: flex;
    flex-direction: row;
    margin-top:25px;
}
#size_list div{
    margin-right: 10px;
}
#date_item{
    text-align: left;
}
#date_list,#time_list{
    display: flex;
    flex-direction: row;
    margin-top:25px;
}
#date_list div{
    margin-right: 10px;
}

#time_list div{
    margin-right: 10px;
}
#patt_item{
    text-align: left;
    margin-top:4%;
}
#patt_list{
    display: flex;
    flex-direction: row;
    margin-top:25px;
}
#patt_list div{
    margin-right: 10px;
}
.margin_top{
    margin-top: 4%;
}
.body_img{
    width:100%;
}
</style>
