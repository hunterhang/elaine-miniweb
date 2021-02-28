<template>
    <div>
        <div v-for="item in list">
            <div v-if="item.type == 1">
                <img v-bind:src="item.url" style="width:100%;"></img>
            </div>
            <div v-if="item.type == 2">
                <!--
            <video  id="video" style="width:100%;object-fit: fill;" v-bind:poster="item.poster" @click="playAndPause"  controls="controls" webkit-playsinline="true" x-webkit-airplay="true"  playsinline="true" x5-video-player-type="h5" x5-video-player-fullscreen="true" preload="auto" >
                <source v-bind:src="item.url" type="video/mp4">
            </video>
            -->
                <iframe frameborder="0" width="100%" height="300px" src="https://v.qq.com/iframe/player.html?vid=m0554lhkx5s&tiny=0&auto=0"
                    allowfullscreen></iframe>
            </div>
        </div>
        <div class="margin_top2 desc" v-if="0">
            <div>我们提供服装设计师的平台</div>
            <div>来展现你的才华吧</div>
        </div>
        <div class="margin_top2" v-if="0">
            <div class="corner_upload" @click="jump">
                上传我的设计
            </div>
        </div>

        <Dialog1 v-model="isShow" v-bind:type="type" title="弹窗标题">
            {{alert_content}}
        </Dialog1>
    </div>
</template>
<script>
    import {
        Cookie
    } from '@/components/comm.js'
    import Dialog1 from '@/components/WeuiDialog.vue'
    export default {
        name: 'customize',
        data() {
            return {
                list: [],
                msg: 'this is desinger page',
                video_obj: {},
                //弹窗
                type: true,
                isShow: false,
                alert_content: "未填写内容"
            }
        },
        mounted: function () {
            this.getDesignerIndex();
            if (Cookie.get("is_first_login") == "true") {
                console.log("get money");
                this.alert_content = "您已经成功领取50元的优惠卷,消费时自动抵扣等价金额!";
                this.isShow = true;
                Cookie.set("is_first_login", "false");
            }
        },
        methods: {
            playAndPause: function () {
                var video_obj = document.getElementById("video");
                if (video_obj.paused) {
                    video_obj.play();
                } else {
                    video_obj.pause();
                }
            },
            getDesignerIndex() {
                let vm = this;
                this.status = 1;

                this.$http.get('http://www.welaine.com/Home/index/get_recommend_designer_info').then(function (response) {
                    var result = response.data;
                    console.log(response.data);
                    if (result.ret == 0) {
                        vm.list = result.list;
                    }
                }).catch(function (error) {
                    console.log(error);
                });

            },
            jump() {
                var url = "/dist/user_upload_product.html?t=123#/";
                location.href = url;
            }
        },
        components: {
            Dialog1
        }
    }
</script>
<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
    .corner_upload {
        border-radius: 4px;
        border: 1px solid #000000;
        padding: 5px;
        text-align: center;
        font-weight: bold;
        line-height: 32px;
    }

    .desc {
        text-align: center;
        color: #b2b2b2;
    }

    .desc div {
        line-height: 35px;
        font-size: 13px;
    }
</style>