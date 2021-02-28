<template>
    <div>
        <div class="banner">
            <div v-if="is_search_model == 0" class="search_button" @click="switch_search">{{nav.text2}}</div>
            <div v-if="is_search_model == 1" class="search_button" @click="switch_search">{{nav.text1}}</div>
            <div>
                <img src="static/index_logo.png" height="60px;" style="margin: 10px auto;">
            </div>
        </div>
        <div class="black_line" style="position: relative;"></div>
        <div :class="c" class="navi">
            <router-link to="designer_list">
                <ul>设计师定制</ul>
            </router-link>
            <router-link to="customize_list">
                <ul>品牌买手</ul>
            </router-link>
            <router-link to="dress_match_list">
                <ul>每日穿搭</ul>
            </router-link>
            <!--<router-link @click="jump()"to="">-->
                <a href="https://mp.weixin.qq.com/s?__biz=MzI5ODUyODY5MQ==&mid=2247484110&idx=1&sn=4f68705f76aae9bb07803577f685e5ae&chksm=eca53812dbd2b104ac252e4136489fd319acdd75550ff3e14c04a57336951c421f0c146427a6&mpshare=1&scene=1&srcid=070558zneIE0KfQKJNUejDFM#rd"><ul>品牌故事</ul></a>
            <!--</router-link>-->
            <router-link to="user_center">
                <ul>个人中心</ul>
            </router-link>
        </div>

        <div class="body" v-if="list.length != 0">
            <Scroll :onRefresh="refresh" :css_style="'width:inherit;top:86px;'" :is_more="page.is_more" :is_loading="page.is_loading">
                <div v-for="(item,index) in show_list">
                    <div class="line1" @click="jump(item.link1)">
                        <img :src="item.img1" style="width:100%;"></img>
                    </div>
                    <!---->
                    <div class="line2" style="display:flex;flex-direction:row-reverse;" @click="jump(item.link2)">
                        <div>
                            <img :src="item.img2[0]" style="width:100%;"></img>
                        </div>
                        <div>
                            <img :src="item.img2[1]" style="width:100%;"></img>
                        </div>
                    </div>
                    <div class="line3" style="display:flex;flex-direction:row-reverse;" @click="jump(item.link3)">
                        <div>
                            <img :src="item.img3[0]" style="width:100%;"></img>
                        </div>
                        <div>
                            <img :src="item.img3[1]" style="width:100%;"></img>
                        </div>
                    </div>
                </div>

            </Scroll>
        </div>
    </div>
</template>
<script>
    export default {
        data() {
            return {
                nav: {
                    text1: '×',
                    text2: '☰',
                },
                c: "hidden",
                is_search_model: 0,
                list: [],
                show_list: [],
                page: {
                    is_more: 1,
                    begin: 0,
                    is_loading: 0
                }
            }
        },
        watch: {
            is_search_model: function (val) {
                if (val == 1) {
                    this.c = "show";
                } else {
                    this.c = "hidden"
                }
            }
        },
        mounted: function () {
            this.get_list();
            //$scroller.get('myScroller').resize()
        },
        methods: {
            jump(){
                //https://mp.weixin.qq.com/s?__biz=MzI5ODUyODY5MQ==&mid=2247484110&idx=1&sn=4f68705f76aae9bb07803577f685e5ae&chksm=eca53812dbd2b104ac252e4136489fd319acdd75550ff3e14c04a57336951c421f0c146427a6&mpshare=1&scene=1&srcid=070558zneIE0KfQKJNUejDFM#rd
                //this.$router.push("https://baidu.com");
                window.location.href="https://mp.weixin.qq.com/s?__biz=MzI5ODUyODY5MQ==&mid=2247484110&idx=1&sn=4f68705f76aae9bb07803577f685e5ae&chksm=eca53812dbd2b104ac252e4136489fd319acdd75550ff3e14c04a57336951c421f0c146427a6&mpshare=1&scene=1&srcid=070558zneIE0KfQKJNUejDFM#rd";
            },
            switch_search: function () {
                this.is_search_model = (this.is_search_model + 1) % 2;
            },
            jump: function (id) {
                this.$router.push({
                    path: 'customize',
                    query: {
                        id: id
                    }
                });
            },
            refresh: function () {
                //this.get_list(done);
                this.page.begin++;
                if (this.page.begin == this.list.length) {
                    this.page.is_more = 0;
                    return;
                }
                console.log("refresh")
                this.show_list.push(this.list[this.page.begin]);
            },
            get_list: function () {
                let self = this;
                this.$store.dispatch('GetIndexPageList').then(rsp => {
                    console.log(rsp);
                    if (rsp.ret == 0) {
                        var arr = [];
                        rsp.result.list.forEach(function (item) {
                            var obj = {};
                            obj.link1 = item.link1;
                            obj.img1 = item.img1;
                            obj.link2 = item.link2;
                            obj.img2 = item.img2.split("|");
                            obj.link3 = item.link3;
                            obj.img3 = item.img3.split("|");
                            self.list.push(obj);
                        });
                    }
                    self.show_list.push(self.list[self.page.begin]);
                })
            }
        }
    }
</script>
<style scoped>
    .search_button {
        position: absolute;
        height: inherit;
        width: 70px;
        font-size: 2rem;
        line-height: 80px;
    }

    .banner {
        text-align: center;
        margin: 0px auto;
        left: 0;
        right: 0;
        background-color: white;
        /* width: 300px; */
        /**position: fixed;*/
    }

    .navi {
        z-index: 1100;
        position: relative;
        overflow: hidden;
        background-color: white;
        width: inherit;
        transition: height 0.5s;
    }

    .navi ul {
        width: 90%;
        border-bottom: 1px solid #CCCCCC;
        line-height: 50px;
        font-weight: 600;
        margin-left: 5%;
    }

    .navi a {
        color: black;
    }

    .body {
        /*margin-top: 85px;**/
    }

    .hidden {
        height: 0px;
    }

    .show {
        height: 280px;
    }

    .black_line {
        border-bottom: 1px solid black;
    }
</style>