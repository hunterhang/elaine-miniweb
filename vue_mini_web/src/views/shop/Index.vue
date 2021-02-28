<template>
    <div>
        <div>
            <router-link to="/"><img v-bind:src="bg_img" style="width:100%;"></img></router-link>
        </div>
        <div id="index_main">
            <div id="index_tab_list1">
                <router-link to="/designer" tag="div" active-class="black">{{tab1_name}}
                </router-link>
                <div class="circle_new">New</div>
                <router-link to="/customize" tag="div" active-class="black">{{tab2_name}}</router-link>
            </div>
            <div id="router_view">
                <router-view></router-view>
            </div>
        </div>
        <div style="text-align:center;">
            <img v-bind:src="scan_img" style="width:100%;margin:0px auto;">
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            bg_img: "/ui/images/bg_v1.jpg",
            tab1_name: '本期设计师故事',
            tab2_name: '本周推荐',
            scan_img: "/ui/images/icon_scan.png",
        }
    }
}
</script>

<style>
#index_main {
    width: 87.4%;
    margin: 0 auto;
    text-align: center;
}

#index_tab_list1 {
    margin-top: 4%;
    display: flex;
    flex-direction: row;
    justify-content: space-between;
    position: relative;
}

#index_tab_list1 div {
    width: 45.8%;
    text-align: center;
    text-decoration: none;
    line-height: 32px;
    font-weight: 700;
    border-radius: 7px;
    border: 1px solid #B2B2B2;
}

#router_view {
    margin-top: 4%;
}

.corner {
    border-radius: 4px;
    border: 1px solid #B2B2B2;
    padding: 2px 5px;
    text-align: center;
}
.circle_new {
    width:30px !important;
    height: 30px;
    border-radius: 15px !important;
    background-color: red;
    line-height: 30px !important;
    position: absolute !important;
    left: 41%;
    top: -15px;
    color:white;
    transform: scale(0.8,0.8);
}
</style>
