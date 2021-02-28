#修改服务号的菜单脚本
```
curl https://api.weixin.qq.com/cgi-bin/menu/create?access_token=8_R2fE9UqNZG19hBGShW6p5CMIOJi3OjV6AzS2ySZMOhebrn35lML6yX5R257GOlmaciaRVtBUcAxTrWummvUPTM
aiWJxhfr3Fg9wXvqYt5o5Yf0fxe12qhSQjmbQOYYcAEAGQQ -d '{ "button": [ { "type": "view", "name": "私人定制", "url": "http://www.welaine.com/dist/index.html#/customize?timestamp=1" }, { 
"type": "view", "name": "WE精选", "url": "http://www.welaine.com/dist/show_center.html#/customize_list?timestamp=1" }, { "name": "WE", "sub_button": [ { "type": "view", "name": "上
传我的设计", "url": "http://www.welaine.com/dist/user_center_designer.html#/?timestamp=1" }, { "type": "view", "name": "个人中心", "url": "http://www.welaine.com/dist/user_center.h
tml#/buy_list?timestamp=1" } ] } ] }'
```