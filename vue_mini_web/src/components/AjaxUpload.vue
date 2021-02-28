<template>
    <div>
        <form id="ajax-upload" action="" method="post" enctype="multipart/form-data">
            <div class="click_area corner">
                <div class="img">
                    <img id="upload_icon" v-bind:src="upload_img">
                </div>
                <div class="file">
                    <input type="file" id="ajax_file" name="submitted" accept="image/png, image/jpeg, image/gif, image/jpg" @change="upload">
                    <input type="submit" id="submit" style="display:none;">
                </div>
            </div>
        </form>
        <Dialog v-model="isShow" v-bind:type="type" title="弹窗标题">
            {{alert_content}}
        </Dialog>
        <!--https://github.com/xyxiao001/vue-cropper/blob/master/example/src/App.vue-->
        <div v-if="img_cute_status == 1" id="img_cute_layer" @click="startCrop">
            <vueCropper ref="cropper" :autoCrop="option.autoCrop" :img="option.img" :outputSize="option.size" :outputType="option.outputType"
                :fixedNumber="option.fixedNumber" :fixed="true"></vueCropper>
        </div>
        <div class="page__bd" id="loading">
            <div v-if="upload_status == 1" class="weui-loadmore">
                <i class="weui-loading"></i>
                <span class="weui-loadmore__tips">正在加载</span>
            </div>
        </div>
    </div>
</template>
<script>
    import Dialog from '@/components/WeuiDialog.vue'
    import vueCropper from '@/components/vue-cropper.vue'
    export default {
        data() {
            return {
                upload_img: "http://www.welaine.com/ui/images/camera_bg.png",
                isShow: false,
                alert_content: "未填写内容",
                type: false,
                img_cute_status: 0,
                upload_status: 0,
                option: {
                    img: "",
                    size: 1,
                    outputType: 'png',
                    autoCrop: true,
                    fixedNumber: [1, 1.5]
                }
            }
        },
        props: {
            onSuccess: {
                type: Function,
                default: undefined,
                require: true
            },
            onError: {
                type: Function,
                default: undefined,
                require: true
            },
            url: {
                type: String,
                require: true
            }
        },
        mounted: function () {
            let vm = this;
            $("#ajax-upload").on("submit", function (e) {
                e.preventDefault();
                var form = e.target;
                var data = new FormData(form);
                //      vm.upload_img = "";
                vm.upload_status = 1;
                $.ajax({
                    url: vm.url,
                    method: form.method,
                    processData: false,
                    contentType: false,
                    data: data,
                    processData: false,
                    success: function (data) {
                        var rsp = $.parseJSON(data);
                        vm.upload_status = 0;
                        if (rsp.ret != 0) {
                            vm.alert_content = rsp.msg;
                            vm.isShow = true;
                            vm.onError(rsp.data.msg);
                            return;
                        } else {
                            vm.upload_img = rsp.data.thumb;
                            vm.onSuccess(rsp.data.thumb);
                        }
                    }
                });
            });
        },
        methods: {
            upload: function () {
                $("#img_cute_layer").css("display", "inline");
                let vm = this;
                $("#submit").click();
                return;
                var file = document.getElementById("ajax_file").files[0];
                if (!/image\/\w+/.test(file.type)) {
                    return;
                }
                var reader = new FileReader();
                reader.readAsDataURL(file);
                reader.onloadend = function (e) {
                    vm.option.img = e.target.result;
                    //vm.src_img = "http://www.welaine.com/ui/img_show/chuanda_00076.png";
                }
            },
            startCrop: function () {
                console.log("startCrop");
                this.$refs.cropper.startCrop();
            }
        },
        components: {
            Dialog,
            vueCropper
        }
    }
</script>
<style>
    #upload_icon {
        width: 100%;
        /**height: 280px;**/
        margin: 0px auto;
    }

    .file {
        position: absolute;
        left: 0px;
    }

    #ajax_file {
        width: 300px;
        height: 280px;
        opacity: 0;
        margin: 0 20px;
    }

    .click_area {
        position: relative;
        display: flex;
        width: 100%;
        margin: 0 auto;
    }

    .click_area div {
        margin: 20px auto;
    }

    #img_cute_layer {
        position: absolute;
        width: 100%;
        z-index: 0;
        height: 800px;
        top: 0px;
        background-color: gray;
        left: 0px;
        opacity: 0.8;
        display: none;
    }
</style>