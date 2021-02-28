<template lang="html">
  <div class="yo-scroll" :style="css_style">
      <slot></slot>
      <footer class="load-more">
        <slot name="load-more">
        <span v-if="is_more==0" class="click_text">没有更多了</span>
        <span v-if="is_more==1" @click="refresh" class="click_text">点击加载更多</span>
        <span v-if="is_loading==1" class="click_text">正在努力加载</span>
        
        </slot>
      </footer>
  </div>
</template>

<script>
export default {
    props: {
        offset: {
            type: Number,
            default: 40
        },
        onRefresh: {
            type: Function,
            default: undefined,
            required: false
        },
        is_more: {
            type: Number,
            default: 0
        },
        is_loading:{
            type:Number,
            default:0
        },
        css_style:{
            type:String,
            default:'',
            required:false
        }
    },
    data() {
        return {
            
        }
    },
    methods: {
        refresh() {
            this.status = 1
            this.onRefresh()
        }
    }
}
</script>
<style scoped>
.yo-scroll {
    position: absolute;
    top: 141px;
    overflow: auto;
    -webkit-overflow-scrolling: touch;
    width: 87.4%;
    bottom:0px;
}
.yo-scroll::-webkit-scrollbar
{
    width: 0px;
    height: 1px;
}

.yo-scroll .load-more {
    height: 3rem;
    display: flex;
    align-items: center;
    justify-content: center;
}

.click_text {
    height: 50px;
    line-height: 50px;
}
</style>