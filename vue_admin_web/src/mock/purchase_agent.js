import { param2Obj } from 'utils';
export default {
    GetPurchaseAgentList: config => {
        //const { email } = JSON.parse(config.body);
        //return userMap[email.split('@')[0]];
        var rsp = {};
        rsp.code = 0;
        rsp.msg = "success";
        rsp.result = {};
        rsp.result.total_size = 11;
        rsp.result["list"] = [
            {
                id: 1,
                title: "定制一期",
                style_list: [
                    { name: "上衣", price: 50000 },
                    { name: "短裙", price: 48000 }
                ],
                is_online: 1,
                order_num: 990,
                visits: 2912312,
                img: "http://www.welaine.com/ui/dress/VOL0001.png",
                created: "2017-03-14 20:23:27",
                start_time: "2017-03-14 20:23:27"
            },
            {
                id: 2,
                title: "定制二期",
                style_list: [
                    { name: "外套", price: 30000 },
                    { name: "短裙", price: 48000 },
                    { name: "内衣", price: 62800 }
                ],
                is_online: 0,
                order_num: 181231,
                visits: 8122410,
                img: "http://www.welaine.com/ui/dress/VOL0002.png",
                created: "2017-03-14 20:23:27",
                start_time: "2017-03-14 20:23:27"
            }
        ];
        return rsp;
    },
    DeletePurchaseAgentRow: config => {
        var rsp = {};
        rsp.code = 0;
        rsp.msg = "success";
        return rsp;
    },
    GetPurchaseAgentDetail: config => {
        var rsp = {};
        rsp.code = 0;
        rsp.msg = "success";
        rsp.result = {
            id: 1,
            title: "定制一期",
            img: "http://www.welaine.com/ui/dress/VOL0001.png",
            desc: [
                "http://www.welaine.com/ui/dress/VOL0001_01.png",
                "http://www.welaine.com/ui/dress/VOL0001_02.png",
                "http://www.welaine.com/ui/dress/VOL0001_03.png",
                "http://www.welaine.com/ui/dress/VOL0001_04.png",
                "http://www.welaine.com/ui/dress/VOL0001_05.png",
                "http://www.welaine.com/ui/dress/VOL0001_06.png"
            ],
            start_time: "2050-01-01 00:00:00",
            visits: 10,
            order_num: 0,
            is_designer: 0,
            is_online: 1,
            item_list: [
                {
                    id: 1,
                    price: 438000
                },
                {
                    id: 3,
                    price: 412100
                }
            ]
        };
        return rsp;
    },
    GetStyleList: config => {
        var rsp = {};
        rsp.code = 0;
        rsp.msg = "success";
        rsp.result = {};
        rsp.result = {
            list: [
                {
                    id: 1,
                    name: "上衣"
                },
                {
                    id: 2,
                    name: "内衣"
                },
                {
                    id: 3,
                    name: "短裙"
                }
            ]
        };
        return rsp;
    },
    GetAddImgPrefix: config => {
        var rsp = {};
        rsp.code = 0;
        rsp.msg = "success";
        rsp.result = {};
        rsp.result = {
            prefix_name: "VOL0001"
        };
        return rsp;
    },
    FormSubmitForBackend: config => {
        var rsp = {};
        rsp.code = 0;
        rsp.msg = "success";
        rsp.result = {};
        rsp.result = {
            id:'1'
        };
        return rsp;
    }
};
