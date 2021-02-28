export function GetGoodsList(where) {
    console.log(where);
    return fetch({
        url: '/home/goods/get_list',
        method: 'post'
    });
}
