import { GetPurchaseAgentList,GetRecommendList,DeletePurchaseAgentRow,GetPurchaseAgentDetail,GetRecommendDetail,GetStyleList,GetAddImgPrefix,FormSubmitForBackend } from 'api/purchase_agent';
import Cookies from 'js-cookie';

const goods = {
    state: {
        user: "",
        status: "",
        email: "",
        code: "",
        uid: undefined,
        auth_type: "",
        token: Cookies.get("Admin-Token"),
        name: "",
        avatar: "",
        introduction: "",
        roles: [],
        setting: {
            articlePlatform: []
        }
    },

    mutations: {
        SET_AUTH_TYPE: (state, type) => {
            state.auth_type = type;
        },
        SET_CODE: (state, code) => {
            state.code = code;
        },
        SET_TOKEN: (state, token) => {
            state.token = token;
        },
        SET_UID: (state, uid) => {
            state.uid = uid;
        },
        SET_EMAIL: (state, email) => {
            state.email = email;
        },
        SET_INTRODUCTION: (state, introduction) => {
            state.introduction = introduction;
        },
        SET_SETTING: (state, setting) => {
            state.setting = setting;
        },
        SET_STATUS: (state, status) => {
            state.status = status;
        },
        SET_NAME: (state, name) => {
            state.name = name;
        },
        SET_AVATAR: (state, avatar) => {
            state.avatar = avatar;
        },
        SET_ROLES: (state, roles) => {
            state.roles = roles;
        },
        LOGIN_SUCCESS: () => {
            console.log("login success");
        },
        LOGOUT_USER: state => {
            state.user = "";
        }
    },

    actions: {
        //获取purchase_agent_list
        GetPurchaseAgentList({ commit }, form) {
            const b_date = form.b_date.trim();
            const title = form.title.trim();
            const e_date = form.e_date.trim();
            return new Promise((resolve, reject) => {
                GetPurchaseAgentList(b_date, e_date, title)
                    .then(response => {
                        const data = response.data;
                        resolve(data);
                    })
                    .catch(error => {
                        reject(error);
                    });
            });
        },
         //获取推荐列表
         GetRecommendList({ commit }, form) {
            const b_date = form.b_date.trim();
            const title = form.title.trim();
            const e_date = form.e_date.trim();
            return new Promise((resolve, reject) => {
                GetRecommendList(b_date, e_date, title)
                    .then(response => {
                        const data = response.data;
                        resolve(data);
                    })
                    .catch(error => {
                        reject(error);
                    });
            });
        },
        GetRecommendDetail({ commit }, id) {
            return new Promise((resolve, reject) => {
                GetRecommendDetail(id)
                    .then(response => {
                        resolve(response.data);
                    })
                    .catch(error => {
                        reject(error);
                    });
            });
        },
        DeletePurchaseAgentRow({ commit }, index) {
            return new Promise((resolve, reject) => {
                DeletePurchaseAgentRow(index)
                    .then(response => {
                        resolve(response.data);
                    })
                    .catch(error => {
                        reject(error);
                    });
            });
        },
        GetPurchaseAgentDetail({ commit }, index) {
            return new Promise((resolve, reject) => {
                GetPurchaseAgentDetail(index)
                    .then(response => {
                        resolve(response.data);
                    })
                    .catch(error => {
                        reject(error);
                    });
            });
        },
        GetStyleList({ commit }) {
            return new Promise((resolve, reject) => {
                GetStyleList()
                    .then(response => {
                        resolve(response.data);
                    })
                    .catch(error => {
                        reject(error);
                    });
            });
        },
        GetAddImgPrefix({ commit },obj) {
            return new Promise((resolve, reject) => {
                GetAddImgPrefix(obj)
                    .then(response => {
                        resolve(response.data);
                    })
                    .catch(error => {
                        reject(error);
                    });
            });
        },
        FormSubmitForBackend({ commit },obj) {
            return new Promise((resolve, reject) => {
                FormSubmitForBackend(obj)
                    .then(response => {
                        resolve(response.data);
                    })
                    .catch(error => {
                        reject(error);
                    });
            });
        }
    }
};

export default goods;
