#!/bin/sh
access_token=`curl "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=wx66ff2fc56ab60b17&secret=460a7d2e37d9e77c881a41c411bfd967" -s |awk -F "\"" ' { print $4 } ' `
openid=$1
echo $access_token >> /tmp/dd.txt
echo $openid >> /tmp/dd.txt
sleep 2
curl -s "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=$access_token" -d "{\"touser\":\"$openid\",\"msgtype\":\"text\",\"text\":{\"content\":\"elainewang送您一张50元现金券，感谢有您！- <a href='http://www.welaine.com/dist/phone.html'>【点击领取】</a>(每人限领一张，支付时自动抵扣)\"}}" >> /tmp/dd.txt
