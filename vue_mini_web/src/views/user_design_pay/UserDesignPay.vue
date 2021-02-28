<template>
    <div>
        <div>
            <img v-bind:src="bg_img" style="width:100%;"></img>
        </div>
        <div id="index_main">
           <Pay></Pay>
        </div>
        <div style="text-align:center;">
            <img v-bind:src="scan_img" style="width:100%;margin:0px auto;">
        </div>
    </div>
</template>

<script>
import Pay from '@/views/user_design_pay/pay.vue'

export default {
    data() {
        return {
            bg_img: "http://www.welaine.com/ui/images/bg_v1.jpg",
            scan_img: "http://www.welaine.com/ui/images/icon_scan.png",
        }
    },
    components:{Pay}
}
</script>

<style>
#index_main {
    width: 87.4%;
    margin: 0 auto;
    text-align: center;
}

#index_tab_list {
    margin-top: 4%;
    display: flex;
    flex-direction: row;
    justify-content: space-between;
}

#index_tab_list div {
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
</style>
