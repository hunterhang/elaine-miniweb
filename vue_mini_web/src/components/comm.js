import axios from 'axios'
function getKeys(obj) {
	var names = [],name = '';
	for (name in obj) {
		if (obj.hasOwnProperty(name)) names.push(name);
	}
	return names;
}
function isPlainObject(value) {
	return !!value && Object.prototype.toString.call(value) === '[object Object]';
}
function isArray(value) { return value instanceof Array }
function toArray(value) {
	return Array.prototype.slice.call(value);
}
var Cookie = {
    get: function (name) {
        var nameEQ = name + "=";
        var ca = document.cookie.split(';');//把cookie分割成组    
        for (var i = 0; i < ca.length; i++) {
            var c = ca[i];//取得字符串    
            while (c.charAt(0) == ' ') {//判断一下字符串有没有前导空格    
                c = c.substring(1, c.length);//有的话，从第二位开始取    
            }
            //如果含有我们要的name
            if (c.indexOf(nameEQ) == 0) {
                return decodeURI(c.substring(nameEQ.length, c.length));//解码并截取我们要值    
            }
        }
        return false;
    },
    set: function (name, value, options) {
        if (isPlainObject(name)) {
            for (var k in name) {
                if (name.hasOwnProperty(k)) this.set(k, name[k], value);
            }
        } else {
            var opt = isPlainObject(options) ? options : { expires: options },
                expires = opt.expires !== undefined ? opt.expires : '',
                expiresType = typeof (expires),
                path = opt.path !== undefined ? ';path=' + opt.path : ';path=/',
                domain = opt.domain ? ';domain=' + opt.domain : '',
                secure = opt.secure ? ';secure' : '';

            //过期时间
            if (expiresType === 'string' && expires !== '') expires = new Date(expires);
            else if (expiresType === 'number') expires = new Date(+new Date + 1000 * 60 * 60 * 24 * expires);
            if (expires !== '' && 'toGMTString' in expires) expires = ';expires=' + expires.toGMTString();


            document.cookie = name + "=" + encodeURI(value) + expires + path + domain + secure;   //转码并赋值    
        }
    },
    del: function (names) {
        names = isArray(names) ? names : toArray(arguments);
        for (var i = 0, l = names.length; i < l; i++) {
            this.set(names[i], '', -1);
        }
        return names;
    },
    clear: function (name) {
        return name ? this.del(name) : this.del(getKeys(this.all()));
    },
    all: function () {
        if (document.cookie === '') return {};
        var cookies = document.cookie.split('; '), result = {};
        for (var i = 0, l = cookies.length; i < l; i++) {
            var item = cookies[i].split('=');
            result[decodeURI(item[0])] = decodeURI(item[1]);
        }
        return result;
    }
};

var Auth = {
    check_auth_code: function (code,curr_url,cookie_version,next) {
        let vm = this;
        axios.get('http://www.welaine.com/Home/Auth/check_auth_code', {
            params: {
                code: code
            }
        }).then(function (response) {
            var names = ["openid","nickname","user_head","v"];
            Cookie.del(names);
            if (response.data.ret == 0) {
                var obj = {};
                obj.openid = response.data.openid;
                obj.nickname = response.data.nickname;
                obj.user_head = response.data.user_head;
                obj.v = cookie_version;
                var ttl = 30 * 3600 * 24;
                Cookie.set(obj,"",ttl);
            }
            //var url =  curr_url.replace(/\?code=(.*)#/, "#"); 
            //location.href = url;
            next();
        }).catch(function (error) {
            console.log(error);
            return -2;
        });
    },
    get: function (name) {
        return decodeURIComponent((new RegExp('[?|&]' + name + '=' + '([^&;]+?)(&|#|;|$)').exec(location.href) || [, ""])[1].replace(/\+/g, '%20')) || null;
    },
    getWxAuthUrl: function (url) {
        var appid = "wx66ff2fc56ab60b17";
        //var auth_url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=" + appid + "&redirect_uri=" + url + "&response_type=code&scope=snsapi_userinfo#wechat_redirect";
        var auth_url ="https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx66ff2fc56ab60b17&redirect_uri="+url+"&response_type=code&scope=snsapi_userinfo#wechat_redirect";
        //return "/dist/?code=011t8gb10Pz3yC1FA8a10cwhb10t8gb0&state=#"
        return auth_url;
    },
    getWxConfig: function () {
        var obj = {};
        obj.appid = "wx66ff2fc56ab60b17";
        return obj;
    }
}

export { Cookie, Auth }
