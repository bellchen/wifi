<?php
/*
1、提供界面让东欣可以给商家OpenID添加wifi设备
	1、东欣让商家在公众号里边输入#wifi#<东欣和商家约定的字符串>，绑定OpenID和<特定字符串>
	2、东欣进入特定页面，通过得到约定字符串给商家插入wifi设备，绑定OpenID和wifi设备，wifi设备设置特定identify
2、商家获取token
	1、商家在公众号输入任意字符串
	2、如果该商家只有一个wifi设备，获得wifi设备的identify
	3、如果商家有多余1个wifi设备
		1、对每个wifi设备，查找对应的identify
		2、返回多个超链接，每个超链接带参数identify，这个超链接由商家来保证不会传播出去
		3、对超链接进行校验：isUsed、校验cookies
	4、根据identify，生成token和超时时间，写入到数据库，返回token给商家
3、用户根据token，提交到服务器
4、服务器收到token，验证token是否存在，验证token是否超时，验证token是否已经占用
5、如果没有占用，允许上网，该token设置一天有效，设置cookies校验token
6、如果占用，校验cookeis是否和token对应，一直则允许上网
库表：

admin,passwd,identify,timeout,token
shopID,OpenID,<约定字符串>,<商家名称>
wifiID,wifiIdentify,shopID,<wifi设备名称>,isUsed,cookies
token,timeout,wifiID,isUsed,cookies

*/