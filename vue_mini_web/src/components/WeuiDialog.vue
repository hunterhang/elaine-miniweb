
<template>
    <div class="js_dialog" v-show="visible" v-bind:value="value">
        <div class="weui-mask"></div>
        <div class="weui-dialog">
            <div class="weui-dialog__bd">
                <slot></slot>
            </div>
            <div v-if="!type" class="weui-dialog__ft">
                <a v-if="type == false" href="javascript:;" @click="ok" class="weui-dialog__btn weui-dialog__btn_primary">
                    知道了
                </a>
            </div>
            <div v-if="type" class="weui-dialog__ft">
                <a href="javascript:;" class="weui-dialog__btn default" @click="cancel">取消</a>
                <a href="javascript:;" class="weui-dialog__btn primary" @click="confirm(data)">确定</a>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'Dialog',
    data() {
        return {
            visible: false,
        }
    },
    watch: {
        value(val) {
            this.visible = val;
        },
        visible(val) {
            this.$emit('input', val);
        }
    },
    props: {
        value: {
            type: Boolean,
            required: true,
            default: false
        },

        //类型：alert,confirm
        type: {
            type: Boolean,
            required: false,
            default: true
        },
        //标题
        title: {
            type: String,
            required: false,
            default: '温馨提示'
        },
        show: {
            type: Boolean,
            required: false,
            default: true
        },
        data: {
            type: String,
            required: false,
            default: ''
        },
        ok_jump: {
            type: String,
            required: false,
            default: ""
        },
        is_need_jump: {
            type: Boolean,
            required: false,
            default: true
        }
    },
    methods: {
        cancel(event_str) {
            this.visible = false;
        },
        ok(event_str) {
            this.visible = false;
            if (this.ok_jump != "") {
                if (this.is_need_jump == true) {
                    location.href = this.ok_jump;
                }
            }
        },
        confirm(event_str) {
            this.$emit("confirm", this.data);
            this.visible = false;
            if (this.ok_jump != "") {
                location.href = this.ok_jump;
            }
        }
    },
    mounted() {
        this.visible = this.value;

    }
}
</script>
