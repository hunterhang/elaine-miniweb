import Mock from 'mockjs';
import loginAPI from './login';
import purchaseAgentApi from './purchase_agent';
import config from '../../config/index.js';


Mock.mock(/\/login\/loginbyemail/, 'post', loginAPI.loginByEmail);
Mock.mock(/\/login\/logout/, 'post', loginAPI.logout);
Mock.mock(/\/user\/info\.*/, 'get', loginAPI.getInfo)
// 登录相关
if(config.dev.mock == true)
{
Mock.mock(/\/admin\/goods\/get_purchase_agent_list\.*/, 'get', purchaseAgentApi.GetPurchaseAgentList)
Mock.mock(/\/admin\/goods\/get_recommend_list\.*/, 'get', purchaseAgentApi.GetRecommendList)
Mock.mock(/\/admin\/goods\/get_recommend_detail\.*/, 'get', purchaseAgentApi.GetRecommendDetail)
Mock.mock(/\/admin\/goods\/delete_purchase_agent_row/, 'post', purchaseAgentApi.DeletePurchaseAgentRow)
Mock.mock(/\/admin\/goods\/get_purchase_agent_detail\.*/, 'get', purchaseAgentApi.GetPurchaseAgentDetail)
Mock.mock(/\/admin\/goods\/get_style_list/, 'get', purchaseAgentApi.GetStyleList)
Mock.mock(/\/admin\/goods\/get_add_img_prefix/, 'get', purchaseAgentApi.GetAddImgPrefix)
Mock.mock(/\/admin\/goods\/form_submit_for_backend/, 'post', purchaseAgentApi.FormSubmitForBackend)
}

export default Mock;
