import fetch from 'utils/fetch';

export function GetPurchaseAgentList(b_date,e_date,title) {
  return fetch({
    url: 'admin/goods/get_purchase_agent_list?b_date='+b_date+"&e_date="+e_date+"&title="+title,
    method: 'get'
  });
}

export function GetRecommendList(b_date,e_date,title) {
  return fetch({
    url: 'admin/goods/get_recommend_list?b_date='+b_date+"&e_date="+e_date+"&title="+title,
    method: 'get'
  });
}
export function GetRecommendDetail(id) {
  return fetch({
    url: 'admin/goods/get_recommend_detail?id='+id,
    method: 'get'
  });
}

export function DeletePurchaseAgentRow(index) {
  const data = {
    index
  };
  return fetch({
    url: '/goods/delete_purchase_agent_row',
    method: 'post',
    data 
  });
}

export function GetPurchaseAgentDetail(index) {
  return fetch({
    url:'/admin/goods/get_dress_detail?id='+index,
    method: 'get'
  })
}

export function GetStyleList() {
  return fetch({
    url: '/admin/goods/get_style_list',
    method: 'get'
  })
}
export function GetAddImgPrefix(obj) {
  return fetch("/admin/goods/get_add_img_prefix?id=" + obj.id + "&cid=" + obj.cid, {
      method: "GET"
  });
}

export function FormSubmitForBackend(obj) {
  return fetch("/admin/goods/form_submit_for_backend", {
      method: "POST",
      data:obj 
  });
}
